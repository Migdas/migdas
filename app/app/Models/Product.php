<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'sku',
        'short_description',
        'description',
        'price',
        'sale_price',
        'is_active',
        'lead_time_days',
        'weight_grams',
        'main_image',
        'gallery',
        'license_author',
        'license_source',
        'license_type',
        'commercial_use_allowed',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'sale_price' => 'decimal:2',
            'is_active' => 'boolean',
            'commercial_use_allowed' => 'boolean',
            'gallery' => 'array',
        ];
    }
}
