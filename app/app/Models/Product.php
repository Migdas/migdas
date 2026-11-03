<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

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
        'length_cm',
        'width_cm',
        'height_cm',
        'parcel_size',
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
            'length_cm' => 'decimal:1',
            'width_cm' => 'decimal:1',
            'height_cm' => 'decimal:1',
        ];
    }
	public function category(): BelongsTo
	{
    		return $this->belongsTo(Category::class);
	}
	public function variants(): HasMany
	{
    		return $this->hasMany(ProductVariant::class);
	}

	/**
	 * Wymiary do wyświetlenia, np. "12 × 8 × 4,5 cm"; null, gdy nie podano.
	 */
	public function dimensionsLabel(): ?string
	{
		if (! $this->length_cm || ! $this->width_cm || ! $this->height_cm) {
			return null;
		}

		$format = fn ($value) => rtrim(rtrim(number_format((float) $value, 1, ',', ''), '0'), ',');

		return $format($this->length_cm) . ' × ' . $format($this->width_cm) . ' × ' . $format($this->height_cm) . ' cm';
	}
}
