<?php

namespace Tests\Feature;

use App\Mail\NewOrderNotification;
use App\Mail\OrderPlaced;
use App\Models\Color;
use App\Models\Material;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ShippingMethod;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Mockery;
use RuntimeException;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    private function variant(int $stock = 2, string $parcelSize = 'A'): ProductVariant
    {
        $product = Product::create([
            'name' => 'Stojak',
            'slug' => 'stojak',
            'price' => 10,
            'parcel_size' => $parcelSize,
        ]);

        $material = Material::create(['name' => 'PLA', 'slug' => 'pla']);
        $color = Color::create(['name' => 'Czarny', 'slug' => 'czarny']);

        return ProductVariant::create([
            'product_id' => $product->id,
            'material_id' => $material->id,
            'color_id' => $color->id,
            'sku' => 'ST-PLA-BLK',
            'stock_quantity' => $stock,
        ]);
    }

    private function cart(ProductVariant $variant): array
    {
        return ['cart' => [$variant->id => ['quantity' => 1]]];
    }

    private function orderData(): array
    {
        return [
            'first_name' => 'Jan',
            'last_name' => 'Nowak',
            'email' => 'jan@example.com',
            'phone' => '500600700',
            'street' => 'Polna',
            'building_number' => '1',
            'postal_code' => '00-001',
            'city' => 'Warszawa',
            'shipping_method' => 'inpost_courier',
            'terms' => '1',
        ];
    }

    public function test_order_button_states_the_obligation_to_pay(): void
    {
        $this->withSession($this->cart($this->variant()))
            ->get('/zamowienie')
            ->assertOk()
            ->assertSee('Zamawiam z obowiązkiem zapłaty');

        $this->get('/regulamin')
            ->assertOk()
            ->assertSee('Zamawiam z obowiązkiem');
    }

    public function test_success_page_confirms_the_email_when_it_was_sent(): void
    {
        Mail::fake();

        $response = $this->withSession($this->cart($this->variant()))
            ->post('/zamowienie', $this->orderData());

        Mail::assertSent(OrderPlaced::class);

        $this->get($response->headers->get('Location'))
            ->assertOk()
            ->assertSee('Potwierdzenie wysłaliśmy')
            ->assertDontSee('Nie udało się wysłać');
    }

    public function test_failed_confirmation_is_shown_to_customer_and_seller(): void
    {
        config(['shop.notification_email' => 'shop@example.com']);

        $seller = Mockery::mock();
        $seller->shouldReceive('send')
            ->once()
            ->withArgs(fn ($mail) =>
                $mail instanceof NewOrderNotification
                && $mail->customerNotified === false
            );

        Mail::shouldReceive('to')
            ->with('jan@example.com')
            ->andThrow(new RuntimeException('SMTP down'));

        Mail::shouldReceive('to')
            ->with('shop@example.com')
            ->andReturn($seller);

        $response = $this->withSession($this->cart($this->variant()))
            ->post('/zamowienie', $this->orderData());

        $this->get($response->headers->get('Location'))
            ->assertOk()
            ->assertSee('Nie udało się wysłać potwierdzenia')
            ->assertDontSee('Potwierdzenie wysłaliśmy');
    }

    public function test_seller_notification_warns_about_failed_confirmation(): void
    {
        Mail::fake();

        $this->withSession($this->cart($this->variant()))
            ->post('/zamowienie', $this->orderData());

        $order = Order::firstOrFail();

        (new NewOrderNotification($order, false))
            ->assertSeeInHtml('nie udało się wysłać potwierdzenia');

        (new NewOrderNotification($order))
            ->assertDontSeeInHtml('nie udało się wysłać potwierdzenia');
    }

    public function test_order_form_is_rate_limited(): void
    {
        Mail::fake();

        for ($i = 0; $i < 10; $i++) {
            $this->post('/zamowienie', [])->assertStatus(302);
        }

        $this->post('/zamowienie', [])
            ->assertStatus(429)
            ->assertSee('Zbyt wiele prób');
    }

    public function test_product_page_uses_shipping_times_from_the_terms(): void
    {
        $this->variant();

        $this->get('/produkt/stojak')
            ->assertOk()
            ->assertSee('Wysyłka w ciągu 3 dni roboczych')
            ->assertSee('wysyłka w ciągu 7 dni roboczych')
            ->assertDontSee('około');
    }

    public function test_checkout_lists_active_shipping_methods_with_prices(): void
    {
        ShippingMethod::where('code', 'dhl_courier')->update(['is_active' => false]);

        $this->withSession($this->cart($this->variant()))
            ->get('/zamowienie')
            ->assertOk()
            ->assertSee('InPost Paczkomat 24/7')
            ->assertSee('Orlen Paczka')
            ->assertSee('Kurier InPost')
            ->assertSee('16,99 zł')
            ->assertDontSee('Kurier DHL');
    }

    public function test_shipping_cost_comes_from_the_panel_price(): void
    {
        Mail::fake();

        ShippingMethod::where('code', 'dhl_courier')->update(['price' => 21.50]);

        $this->withSession($this->cart($this->variant()))
            ->post('/zamowienie', [
                ...$this->orderData(),
                'shipping_method' => 'dhl_courier',
            ])
            ->assertRedirect();

        $order = Order::firstOrFail();

        $this->assertSame('dhl_courier', $order->shipping_method);
        $this->assertSame('Kurier DHL', $order->shipping_name);
        $this->assertEquals(21.50, $order->shipping_cost);
        $this->assertEquals(31.50, $order->total);
        $this->assertNull($order->pickup_point);
    }

    public function test_locker_delivery_needs_point_code_instead_of_address(): void
    {
        Mail::fake();

        $data = [
            ...$this->orderData(),
            'shipping_method' => 'inpost_locker',
        ];

        unset($data['street'], $data['building_number'], $data['postal_code'], $data['city']);

        $cart = $this->cart($this->variant());

        $this->withSession($cart)
            ->post('/zamowienie', $data)
            ->assertSessionHasErrors('pickup_point');

        $this->assertSame(0, Order::count());

        $this->withSession($cart)
            ->post('/zamowienie', [...$data, 'pickup_point' => ' waw01m '])
            ->assertRedirect();

        $order = Order::firstOrFail();

        $this->assertSame('WAW01M', $order->pickup_point);
        $this->assertSame('', $order->street);
        $this->assertEquals(13.99, $order->shipping_cost);
        $this->assertEquals(23.99, $order->total);

        (new OrderPlaced($order))
            ->assertSeeInHtml('WAW01M')
            ->assertSeeInHtml('InPost Paczkomat 24/7');
    }

    public function test_inactive_or_unknown_shipping_method_is_rejected(): void
    {
        Mail::fake();

        ShippingMethod::where('code', 'dhl_courier')->update(['is_active' => false]);

        $cart = $this->cart($this->variant());

        foreach (['dhl_courier', 'courier', 'pickup'] as $code) {
            $this->withSession($cart)
                ->post('/zamowienie', [...$this->orderData(), 'shipping_method' => $code])
                ->assertSessionHasErrors('shipping_method');
        }

        $this->assertSame(0, Order::count());
    }

    public function test_terms_list_shipping_prices(): void
    {
        $this->get('/regulamin')
            ->assertOk()
            ->assertSee('InPost Paczkomat 24/7: gabaryt A - 13,99 zł, gabaryt B - 15,99 zł, gabaryt C - 17,99 zł')
            ->assertSee('Kurier DHL: gabaryt A - 17,99 zł, gabaryt B - 19,99 zł, gabaryt C - 22,99 zł, duża paczka - 29,99 zł');
    }

    public function test_shipping_price_depends_on_the_parcel_size(): void
    {
        Mail::fake();

        $cart = $this->cart($this->variant(parcelSize: 'C'));

        $this->withSession($cart)
            ->get('/zamowienie')
            ->assertOk()
            ->assertSee('gabaryt C')
            ->assertSee('17,99 zł')
            ->assertSee('21,99 zł');

        $this->withSession($cart)
            ->post('/zamowienie', $this->orderData())
            ->assertRedirect();

        $order = Order::firstOrFail();

        $this->assertSame('C', $order->parcel_size);
        $this->assertEquals(21.99, $order->shipping_cost);
        $this->assertEquals(31.99, $order->total);
    }

    public function test_large_parcel_can_only_go_by_courier(): void
    {
        Mail::fake();

        $cart = $this->cart($this->variant(parcelSize: 'D'));

        $this->withSession($cart)
            ->get('/zamowienie')
            ->assertOk()
            ->assertSee('tylko wysyłka kurierem')
            ->assertDontSee('InPost Paczkomat 24/7')
            ->assertDontSee('Orlen Paczka')
            ->assertSee('29,99 zł');

        $this->withSession($cart)
            ->post('/zamowienie', [
                ...$this->orderData(),
                'shipping_method' => 'inpost_locker',
                'pickup_point' => 'WAW01M',
            ])
            ->assertSessionHasErrors('shipping_method');

        $this->assertSame(0, Order::count());
    }

    public function test_product_page_shows_dimensions(): void
    {
        $this->variant()->product->update([
            'length_cm' => 12,
            'width_cm' => 8.5,
            'height_cm' => 4,
            'weight_grams' => 120,
        ]);

        $this->get('/produkt/stojak')
            ->assertOk()
            ->assertSee('12 × 8,5 × 4 cm')
            ->assertSee('120 g');
    }
}
