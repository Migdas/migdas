<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Kolejne ceny produktu lub wariantu - podstawa najniższej ceny z 30 dni.
 */
class PriceHistory extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = [
        'price',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
        ];
    }

    public function priceable(): MorphTo
    {
        return $this->morphTo();
    }
}
