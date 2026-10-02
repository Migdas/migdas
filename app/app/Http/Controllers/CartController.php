<?php

namespace App\Http\Controllers;

use App\Models\ProductVariant;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return view('cart.index', [
                'items' => collect(),
                'total' => 0,
            ]);
        }

        $variants = ProductVariant::query()
            ->with([
                'product',
                'material',
                'color',
            ])
            ->whereIn('id', array_keys($cart))
            ->where('is_active', true)
            ->whereHas(
                'product',
                fn ($query) => $query->where('is_active', true)
            )
            ->get();

        // Usuwamy z koszyka pozycje wycofane ze sprzedaży.
        session()->put(
            'cart',
            array_intersect_key($cart, $variants->keyBy('id')->all())
        );

        $items = $variants->map(function ($variant) use ($cart) {

            $quantity = $cart[$variant->id]['quantity'];

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

        return view('cart.index', [
            'items' => $items,
            'total' => $items->sum('total'),
        ]);
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'variant_id' => [
                'required',
                'integer',
                'exists:product_variants,id',
            ],
            'quantity' => [
                'required',
                'integer',
                'min:1',
                'max:99',
            ],
        ]);

        $variant = ProductVariant::query()
            ->with('product')
            ->findOrFail($validated['variant_id']);

        abort_unless(
            $variant->is_active && $variant->product->is_active,
            404
        );

        $cart = session()->get('cart', []);

        $currentQuantity =
            $cart[$variant->id]['quantity'] ?? 0;

        $cart[$variant->id] = [
            'quantity' => min(
                $currentQuantity + $validated['quantity'],
                99
            ),
        ];

        session()->put('cart', $cart);

        return redirect()
            ->route('cart.index')
            ->with('success', 'Produkt został dodany do koszyka.');
    }

    public function update(Request $request, ProductVariant $variant)
    {
        $validated = $request->validate([
            'quantity' => [
                'required',
                'integer',
                'min:1',
                'max:99',
            ],
        ]);

        $cart = session()->get('cart', []);

        if (isset($cart[$variant->id])) {
            $cart[$variant->id]['quantity'] =
                $validated['quantity'];

            session()->put('cart', $cart);
        }

        return redirect()
            ->route('cart.index')
            ->with('success', 'Koszyk został zaktualizowany.');
    }

    public function remove(ProductVariant $variant)
    {
        $cart = session()->get('cart', []);

        unset($cart[$variant->id]);

        session()->put('cart', $cart);

        return redirect()
            ->route('cart.index')
            ->with('success', 'Produkt został usunięty z koszyka.');
    }
}
