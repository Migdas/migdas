<?php

namespace Tests\Feature;

use App\Filament\Resources\Orders\Pages\EditOrder;
use App\Mail\OrderShipped;
use App\Models\Order;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Tests\TestCase;

class OrderShippingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Filament::setCurrentPanel(Filament::getPanel('admin'));

        $admin = User::factory()->create();
        $admin->forceFill(['is_admin' => true])->save();

        $this->actingAs($admin);
    }

    private function order(): Order
    {
        return Order::create([
            'number' => 'MIG-1',
            'status' => 'processing',
            'payment_status' => 'paid',
            'first_name' => 'Jan',
            'last_name' => 'Nowak',
            'email' => 'jan@example.com',
            'phone' => '500600700',
            'street' => 'Polna',
            'building_number' => '1',
            'postal_code' => '00-001',
            'city' => 'Warszawa',
            'shipping_method' => 'courier',
            'shipping_cost' => 16.99,
            'products_total' => 10,
            'total' => 26.99,
        ]);
    }

    private function save(Order $order, array $data)
    {
        return Livewire::test(EditOrder::class, ['record' => $order->getRouteKey()])
            ->fillForm($data)
            ->call('save');
    }

    public function test_shipped_status_requires_tracking_details(): void
    {
        Mail::fake();

        $this->save($this->order(), ['status' => 'shipped'])
            ->assertHasFormErrors(['carrier', 'tracking_number']);

        Mail::assertNothingSent();
    }

    public function test_marking_as_shipped_emails_the_customer_once(): void
    {
        Mail::fake();

        $order = $this->order();

        $data = ['status' => 'shipped', 'carrier' => 'DHL', 'tracking_number' => 'ABC123'];

        $this->save($order, $data)->assertHasNoFormErrors();

        Mail::assertSent(
            OrderShipped::class,
            fn ($mail) => $mail->hasTo('jan@example.com')
        );

        $this->assertNotNull($order->fresh()->shipped_at);

        $this->save($order->fresh(), $data)->assertHasNoFormErrors();

        Mail::assertSentCount(1);
    }

    public function test_other_statuses_send_nothing(): void
    {
        Mail::fake();

        $this->save($this->order(), ['status' => 'ready'])->assertHasNoFormErrors();

        Mail::assertNothingSent();
    }

    public function test_shipped_email_contains_the_tracking_number(): void
    {
        $order = $this->order();
        $order->update(['carrier' => 'DHL', 'tracking_number' => 'ABC123']);

        (new OrderShipped($order))
            ->assertSeeInHtml('ABC123')
            ->assertSeeInHtml('DHL');
    }
}
