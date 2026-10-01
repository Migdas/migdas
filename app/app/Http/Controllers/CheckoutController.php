<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    private function cartItems()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return collect();
        }

        $variants = ProductVariant::query()
            ->with(['product', 'material', 'color'])
            ->whereIn('id', array_keys($cart))
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
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],

            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],

            'street' => ['required', 'string', 'max:255'],
            'building_number' => ['required', 'string', 'max:30'],
            'apartment_number' => ['nullable', 'string', 'max:30'],

            'postal_code' => [
                'required',
                'regex:/^\d{2}-\d{3}$/',
            ],

            'city' => ['required', 'string', 'max:150'],

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

        $productsTotal = (float) $items->sum('total');

        $shippingCost = match ($validated['shipping_method']) {
            'courier' => 16.99,
            'pickup' => 0.00,
        };

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
            }

            return $order;
        });

        session()->forget('cart');

        return redirect()
            ->route('checkout.success', $order);
    }

    public function success(Order $order)
    {
        return view('checkout.success', compact('order'));
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
