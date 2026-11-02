<?php

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('price_histories', function (Blueprint $table) {
            $table->id();
            $table->morphs('priceable');
            $table->decimal('price', 10, 2);
            $table->timestamp('created_at')->nullable();
        });

        // Punkt wyjścia historii: ceny obowiązujące w chwili migracji.
        foreach ([Product::class, ProductVariant::class] as $model) {
            DB::table((new $model)->getTable())
                ->selectRaw('id, COALESCE(sale_price, price) as current_price')
                ->whereRaw('COALESCE(sale_price, price) IS NOT NULL')
                ->orderBy('id')
                ->get()
                ->each(fn ($row) => DB::table('price_histories')->insert([
                    'priceable_type' => $model,
                    'priceable_id' => $row->id,
                    'price' => $row->current_price,
                    'created_at' => now(),
                ]));
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('price_histories');
    }
};
