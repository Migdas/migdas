<?php

namespace Tests\Feature;

use App\Models\Color;
use App\Models\Material;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StorefrontTest extends TestCase
{
    use RefreshDatabase;

    public function test_sitemap_lists_only_active_products(): void
    {
        Product::create(['name' => 'Stojak', 'slug' => 'stojak', 'price' => 10]);
        Product::create(['name' => 'Ukryty', 'slug' => 'ukryty', 'price' => 10, 'is_active' => false]);

        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml')
            ->assertSee('/produkt/stojak')
            ->assertDontSee('/produkt/ukryty');
    }

    public function test_cart_with_items_shows_the_shipping_cost(): void
    {
        $product = Product::create(['name' => 'Stojak', 'slug' => 'stojak', 'price' => 10]);

        $this->assertStringContainsString(
            '16,99 zł',
            view('cart.index', ['items' => collect([[
                'variant' => $this->variantFor($product),
                'quantity' => 1,
                'unit_price' => 10.0,
                'total' => 10.0,
            ]]), 'total' => 10.0])->render()
        );
    }

    private function variantFor(Product $product)
    {
        return $product->variants()->create([
            'material_id' => Material::create(['name' => 'PLA', 'slug' => 'pla'])->id,
            'color_id' => Color::create(['name' => 'Czarny', 'slug' => 'czarny'])->id,
            'sku' => 'ST-1',
        ])->load(['product', 'material', 'color']);
    }
}
