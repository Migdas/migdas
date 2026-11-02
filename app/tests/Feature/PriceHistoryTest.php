<?php

namespace Tests\Feature;

use App\Models\Color;
use App\Models\Material;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class PriceHistoryTest extends TestCase
{
    use RefreshDatabase;

    private function product(float $price = 100): Product
    {
        return Product::create(['name' => 'Stojak', 'slug' => 'stojak', 'price' => $price]);
    }

    private function changePrice(Product $product, string $date, array $prices): void
    {
        Carbon::setTestNow($date);

        $product->update($prices);
    }

    public function test_price_without_sale_has_no_lowest_price(): void
    {
        $this->assertNull($this->product()->fresh()->lowestPriceBeforeSale());
    }

    public function test_lowest_price_comes_from_the_30_days_before_the_reduction(): void
    {
        Carbon::setTestNow('2026-01-01');
        $product = $this->product(100);

        $this->changePrice($product, '2026-02-01', ['price' => 80]);
        $this->changePrice($product, '2026-02-10', ['price' => 90]);
        $this->changePrice($product, '2026-02-20', ['sale_price' => 50]);

        $this->assertSame(80.0, $product->fresh()->lowestPriceBeforeSale());
    }

    public function test_prices_older_than_30_days_are_ignored(): void
    {
        Carbon::setTestNow('2026-01-01');
        $product = $this->product(60);

        $this->changePrice($product, '2026-01-10', ['price' => 100]);
        $this->changePrice($product, '2026-03-01', ['sale_price' => 50]);

        $this->assertSame(100.0, $product->fresh()->lowestPriceBeforeSale());
    }

    public function test_price_active_when_the_window_opens_counts(): void
    {
        Carbon::setTestNow('2026-01-01');
        $product = $this->product(70);

        $this->changePrice($product, '2026-02-25', ['price' => 100]);
        $this->changePrice($product, '2026-03-01', ['sale_price' => 50]);

        $this->assertSame(70.0, $product->fresh()->lowestPriceBeforeSale());
    }

    public function test_second_reduction_counts_the_first_one(): void
    {
        Carbon::setTestNow('2026-01-01');
        $product = $this->product(100);

        $this->changePrice($product, '2026-03-01', ['sale_price' => 80]);
        $this->changePrice($product, '2026-03-10', ['sale_price' => 60]);

        $this->assertSame(80.0, $product->fresh()->lowestPriceBeforeSale());
    }

    public function test_regular_price_is_the_fallback_without_earlier_history(): void
    {
        $product = Product::create([
            'name' => 'Stojak',
            'slug' => 'stojak',
            'price' => 100,
            'sale_price' => 50,
        ]);

        $this->assertSame(100.0, $product->fresh()->lowestPriceBeforeSale());
    }

    public function test_storefront_shows_the_lowest_price_on_sale(): void
    {
        Carbon::setTestNow('2026-01-01');
        $product = $this->product(100);

        $this->changePrice($product, '2026-03-01', ['sale_price' => 50]);

        $this->get('/')->assertOk()->assertSee('Najniższa cena z 30 dni przed obniżką');

        $this->get('/produkt/stojak')
            ->assertOk()
            ->assertSee('Cena regularna')
            ->assertSee('100,00 zł');
    }

    public function test_variant_on_sale_exposes_its_own_lowest_price(): void
    {
        $product = $this->product(100);

        Carbon::setTestNow('2026-01-01');

        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'material_id' => Material::create(['name' => 'PLA', 'slug' => 'pla'])->id,
            'color_id' => Color::create(['name' => 'Czarny', 'slug' => 'czarny'])->id,
            'sku' => 'ST-PLA-BLK',
            'price' => 70,
        ]);

        Carbon::setTestNow('2026-01-10');
        $variant->update(['sale_price' => 40]);

        $this->get('/produkt/stojak')
            ->assertOk()
            ->assertSee('data-regular="70.00"', false)
            ->assertSee('data-lowest="70"', false);
    }
}
