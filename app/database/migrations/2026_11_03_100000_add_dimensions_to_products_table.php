<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Wymiary gotowego wydruku w cm.
            $table->decimal('length_cm', 6, 1)->nullable()->after('weight_grams');
            $table->decimal('width_cm', 6, 1)->nullable()->after('length_cm');
            $table->decimal('height_cm', 6, 1)->nullable()->after('width_cm');

            // Gabaryt paczki (A, B, C, D = duża) - decyduje o cenie wysyłki.
            $table->string('parcel_size', 5)->default('A')->after('height_cm');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['length_cm', 'width_cm', 'height_cm', 'parcel_size']);
        });
    }
};
