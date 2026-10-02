<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index(Request $request)
    {
        // Parametry mogą przyjść jako tablica (?search[]=x) - bierzemy tylko tekst.
        $category = is_string($request->query('category'))
            ? trim($request->query('category'))
            : '';

        $search = is_string($request->query('search'))
            ? trim($request->query('search'))
            : '';

        $products = Product::query()
            ->where('is_active', true)
            ->with('category')
            ->when(
                $category !== '',
                fn ($query) => $query->whereHas(
                    'category',
                    fn ($categoryQuery) =>
                        $categoryQuery->where('slug', $category)
                )
            )
            ->when(
                $search !== '',
                fn ($query) => $query->where(
                    'name',
                    'like',
                    '%' . addcslashes($search, '\\%_') . '%'
                )
            )
            ->orderByDesc('created_at')
            ->paginate(12)
            ->appends(array_filter([
                'category' => $category,
                'search' => $search,
            ], fn ($value) => $value !== ''));

        $categories = Category::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('store.index', compact(
            'products',
            'categories',
            'category',
            'search'
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
