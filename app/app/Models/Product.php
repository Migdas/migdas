<?php

namespace App\Models;

use App\Models\Concerns\HasPriceHistory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory, HasPriceHistory;

    protected $fillable = [
	'category_id',
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
	public function category(): BelongsTo
	{
    		return $this->belongsTo(Category::class);
	}
	public function regularPrice(): ?string
	{
    		return $this->price;
	}
	public function variants(): HasMany
	{
    		return $this->hasMany(ProductVariant::class);
	}
}
