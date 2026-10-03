<?php

namespace App\Http\Controllers;

use App\Mail\NewOrderNotification;
use App\Mail\OrderPlaced;
use App\Models\Order;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Throwable;

class CheckoutController extends Controller
{
    public const SHIPPING_COSTS = [
        'courier' => 16.99,
        'pickup' => 0.00,
    ];

    private function cartItems()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return collect();
        }

        // Tylko warianty, które nadal są w sprzedaży.
        $variants = ProductVariant::query()
            ->with(['product', 'material', 'color'])
            ->whereIn('id', array_keys($cart))
            ->where('is_active', true)
            ->whereHas(
                'product',
                fn ($query) => $query->where('is_active', true)
            )
            ->get();

        return $variants->map(function ($variant) use ($cart) {

            $quantity = (int) $cart[$variant->id]['quantity'];

            $unitPrice = (float) (
                $variant->sale_price
                ?? $variant->price
                ?? $variant->product->sale_price
                ?? $variant->product->price
            );

            return [
                'variant' => $variant,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'total' => $unitPrice * $quantity,
            ];
        });
    }

    public function index()
    {
        $items = $this->cartItems();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index');
        }

        $productsTotal = $items->sum('total');

        return view('checkout.index', [
            'items' => $items,
            'productsTotal' => $productsTotal,
            'shippingCosts' => self::SHIPPING_COSTS,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],

            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],

            // Adres jest wymagany tylko przy dostawie kurierem.
            'street' => [
                'required_if:shipping_method,courier',
                'nullable',
                'string',
                'max:255',
            ],
            'building_number' => [
                'required_if:shipping_method,courier',
                'nullable',
                'string',
                'max:30',
            ],
            'apartment_number' => ['nullable', 'string', 'max:30'],

            'postal_code' => [
                'required_if:shipping_method,courier',
                'nullable',
                'regex:/^\d{2}-\d{3}$/',
            ],

            'city' => [
                'required_if:shipping_method,courier',
                'nullable',
                'string',
                'max:150',
            ],

            'shipping_method' => [
                'required',
                'in:courier,pickup',
            ],

            'customer_note' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'terms' => ['accepted'],
        ]);

        $items = $this->cartItems();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index');
        }

        // Coś z koszyka zostało w międzyczasie wycofane ze sprzedaży.
        if ($items->count() !== count(session()->get('cart', []))) {
            return redirect()
                ->route('cart.index')
                ->with(
                    'error',
                    'Część produktów z koszyka nie jest już dostępna. Sprawdź koszyk i złóż zamówienie ponownie.'
                );
        }

        $productsTotal = (float) $items->sum('total');

        $shippingCost = self::SHIPPING_COSTS[$validated['shipping_method']];

        // Kolumny adresu w tabeli orders nie przyjmują NULL.
        if ($validated['shipping_method'] === 'pickup') {
            $validated['street'] = '';
            $validated['building_number'] = '';
            $validated['apartment_number'] = null;
            $validated['postal_code'] = '';
            $validated['city'] = '';
        }

        $order = DB::transaction(function () use (
            $validated,
            $items,
            $productsTotal,
            $shippingCost
        ) {
            $order = Order::create([
                'number' => $this->generateOrderNumber(),

                'status' => 'new',
                'payment_status' => 'unpaid',

                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],

                'email' => $validated['email'],
                'phone' => $validated['phone'],

                'street' => $validated['street'],
                'building_number' => $validated['building_number'],
                'apartment_number' =>
                    $validated['apartment_number'] ?? null,

                'postal_code' => $validated['postal_code'],
                'city' => $validated['city'],

                'shipping_method' =>
                    $validated['shipping_method'],

                'shipping_cost' => $shippingCost,

                'products_total' => $productsTotal,

                'total' =>
                    $productsTotal + $shippingCost,

                'customer_note' =>
                    $validated['customer_note'] ?? null,
            ]);

            foreach ($items as $item) {
                $variant = $item['variant'];

                $order->items()->create([
                    'product_variant_id' => $variant->id,

                    'product_name' =>
                        $variant->product->name,

                    'variant_sku' =>
                        $variant->sku,

                    'material_name' =>
                        $variant->material->name,

                    'color_name' =>
                        $variant->color->name,

                    'unit_price' =>
                        $item['unit_price'],

                    'quantity' =>
                        $item['quantity'],

                    'total' =>
                        $item['total'],
                ]);

                // Zdejmujemy sztuki ze stanu. Stan nie schodzi poniżej zera -
                // nadwyżka jest realizowana jako druk na zamówienie.
                $quantity = (int) $item['quantity'];

                ProductVariant::query()
                    ->whereKey($variant->id)
                    ->update([
                        'stock_quantity' => DB::raw(
                            "CASE WHEN stock_quantity > {$quantity} " .
                            "THEN stock_quantity - {$quantity} ELSE 0 END"
                        ),
                    ]);
            }

            return $order;
        });

        session()->forget('cart');
        session()->push('placed_orders', $order->id);

        $this->sendOrderEmails($order);

        return redirect()
            ->route('checkout.success', $order);
    }

    public function success(Order $order)
    {
        // Potwierdzenie widzi tylko ten, kto złożył zamówienie w tej sesji.
        abort_unless(
            in_array($order->id, session()->get('placed_orders', []), true),
            404
        );

        $order->load('items');

        return view('checkout.success', compact('order'));
    }

    private function sendOrderEmails(Order $order): void
    {
        $order->load('items');

        // Zamówienie jest już zapisane - problem z pocztą nie może
        // zakończyć się błędem dla klienta, więc tylko go logujemy.
        try {
            Mail::to($order->email)->send(new OrderPlaced($order));
        } catch (Throwable $e) {
            report($e);
        }

        if (filled(config('shop.notification_email'))) {
            try {
                Mail::to(config('shop.notification_email'))
                    ->send(new NewOrderNotification($order));
            } catch (Throwable $e) {
                report($e);
            }
        }
    }

    private function generateOrderNumber(): string
    {
        do {
            $number =
                'MIG-' .
                now()->format('Ymd') .
                '-' .
                strtoupper(
                    substr(bin2hex(random_bytes(4)), 0, 6)
                );
        } while (
            Order::where('number', $number)->exists()
        );

        return $number;
    }
}
