<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->nullable()->unique();

            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();

            $table->decimal('price', 10, 2);
            $table->decimal('sale_price', 10, 2)->nullable();

            $table->boolean('is_active')->default(true);

            $table->unsignedSmallInteger('lead_time_days')->default(3);
            $table->unsignedInteger('weight_grams')->nullable();

            $table->string('main_image')->nullable();
            $table->json('gallery')->nullable();

            // Informacje o projekcie 3D / licencji
            $table->string('license_author')->nullable();
            $table->string('license_source')->nullable();
            $table->string('license_type')->nullable();
            $table->boolean('commercial_use_allowed')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
