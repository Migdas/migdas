<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::query()
            ->where('is_active', true)
            ->with('category')
            ->when(
                $request->filled('category'),
                fn ($query) => $query->whereHas(
                    'category',
                    fn ($categoryQuery) =>
                        $categoryQuery->where('slug', $request->category)
                )
            )
            ->when(
                $request->filled('search'),
                fn ($query) => $query->where(
                    'name',
                    'like',
                    '%' . $request->search . '%'
                )
            )
            ->orderByDesc('created_at')
            ->paginate(12)
            ->withQueryString();

        $categories = Category::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('store.index', compact(
            'products',
            'categories'
        ));
    }

    public function show(Product $product)
    {
        abort_unless($product->is_active, 404);

        $product->load([
            'category',
            'variants' => fn ($query) =>
                $query
                    ->where('is_active', true)
                    ->with(['material', 'color'])
                    ->orderBy('sort_order'),
        ]);

        return view('store.show', compact('product'));
    }
}
