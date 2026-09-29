<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'material_id',
        'color_id',
        'sku',
        'price',
        'sale_price',
        'stock_quantity',
        'weight_grams',
        'lead_time_days',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'sale_price' => 'decimal:2',
            'stock_quantity' => 'integer',
            'weight_grams' => 'integer',
            'lead_time_days' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class);
    }

    public static function generateSku(
        int $productId,
        int $materialId,
        int $colorId
    ): string {
        $product = Product::findOrFail($productId);
        $material = Material::findOrFail($materialId);
        $color = Color::findOrFail($colorId);

        // Jeśli produkt nie ma własnego SKU, używamy jego ID.
        $productPart = filled($product->sku)
            ? $product->sku
            : 'MIG-P' . $product->id;

        $materialPart = filled($material->code)
            ? $material->code
            : $material->slug;

        $colorPart = filled($color->code)
            ? $color->code
            : $color->slug;

        return Str::upper(
            Str::slug(
                $productPart . '-' . $materialPart . '-' . $colorPart,
                '-'
            )
        );
    }

    protected static function booted(): void
    {
        static::saving(function (ProductVariant $variant): void {
            if (
                ! $variant->product_id ||
                ! $variant->material_id ||
                ! $variant->color_id
            ) {
                return;
            }

            // Czy taka kombinacja już istnieje?
            $duplicateQuery = static::query()
                ->where('product_id', $variant->product_id)
                ->where('material_id', $variant->material_id)
                ->where('color_id', $variant->color_id);

            // Przy edycji nie traktujemy bieżącego wariantu jako duplikatu.
            if ($variant->exists) {
                $duplicateQuery->where('id', '!=', $variant->getKey());
            }

            if ($duplicateQuery->exists()) {
                throw ValidationException::withMessages([
                    'color_id' => 'Ten produkt ma już wariant z wybranym materiałem i kolorem.',
                ]);
            }

            // SKU zawsze wynika z produktu + materiału + koloru.
            $variant->sku = static::generateSku(
                $variant->product_id,
                $variant->material_id,
                $variant->color_id
            );
        });
    }
}
