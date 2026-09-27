<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'code',
        'description',
        'density_g_cm3',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'density_g_cm3' => 'decimal:3',
            'is_active' => 'boolean',
        ];
    }
    public function variants(): HasMany
	{
   	 return $this->hasMany(ProductVariant::class);
	}

}
