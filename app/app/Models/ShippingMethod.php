<?php

namespace App\Models;

use App\Support\ParcelSize;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ShippingMethod extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'price',
        'price_b',
        'price_c',
        'price_large',
        'requires_point',
        'point_map_url',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'price_b' => 'decimal:2',
            'price_c' => 'decimal:2',
            'price_large' => 'decimal:2',
            'requires_point' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function scopeAvailable(Builder $query): Builder
    {
        return $query
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('price');
    }

    /**
     * Cena wysyłki dla gabarytu paczki; null = metoda niedostępna dla tego gabarytu.
     */
    public function priceFor(string $parcelSize): ?float
    {
        $column = ParcelSize::PRICE_COLUMNS[$parcelSize] ?? 'price';
        $price = $this->getAttribute($column);

        return $price === null ? null : (float) $price;
    }
}
