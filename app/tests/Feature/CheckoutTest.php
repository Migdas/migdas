<?php

namespace Tests\Feature;

use App\Mail\NewOrderNotification;
use App\Mail\OrderPlaced;
use App\Models\Color;
use App\Models\Material;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Mockery;
use RuntimeException;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    private function variant(int $stock = 2): ProductVariant
    {
        $product = Product::create([
            'name' => 'Stojak',
            'slug' => 'stojak',
            'price' => 10,
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
            'shipping_method' => 'courier',
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
}
