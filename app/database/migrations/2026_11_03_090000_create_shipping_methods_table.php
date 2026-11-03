<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipping_methods', function (Blueprint $table) {
            $table->id();

            // Kod zapisywany w zamówieniu, np. inpost_locker.
            $table->string('code')->unique();

            $table->string('name');
            $table->string('description')->nullable();

            $table->decimal('price', 10, 2);

            // Dostawa do punktu (paczkomat, punkt Orlen Paczki) zamiast pod adres.
            $table->boolean('requires_point')->default(false);

            // Strona z mapą punktów, podpowiadana klientowi.
            $table->string('point_map_url')->nullable();

            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();
        });

        // Ceny startowe - do zmiany w panelu (Metody dostawy).
        $now = now();

        DB::table('shipping_methods')->insert([
            [
                'code' => 'inpost_locker',
                'name' => 'InPost Paczkomat 24/7',
                'description' => 'Odbiór w wybranym paczkomacie',
                'price' => 13.99,
                'requires_point' => true,
                'point_map_url' => 'https://inpost.pl/znajdz-paczkomat',
                'is_active' => true,
                'sort_order' => 10,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'code' => 'orlen_point',
                'name' => 'Orlen Paczka',
                'description' => 'Odbiór na stacji Orlen, w kiosku Ruchu lub automacie',
                'price' => 10.99,
                'requires_point' => true,
                'point_map_url' => 'https://www.orlenpaczka.pl/znajdz-punkt/',
                'is_active' => true,
                'sort_order' => 20,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'code' => 'inpost_courier',
                'name' => 'Kurier InPost',
                'description' => 'Dostawa pod wskazany adres',
                'price' => 16.99,
                'requires_point' => false,
                'point_map_url' => null,
                'is_active' => true,
                'sort_order' => 30,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'code' => 'dhl_courier',
                'name' => 'Kurier DHL',
                'description' => 'Dostawa pod wskazany adres',
                'price' => 17.99,
                'requires_point' => false,
                'point_map_url' => null,
                'is_active' => true,
                'sort_order' => 40,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        Schema::table('orders', function (Blueprint $table) {
            // Nazwa metody w chwili zamówienia - zmiana w panelu nie zmienia starych zamówień.
            $table->string('shipping_name')->nullable()->after('shipping_method');
            $table->string('pickup_point', 100)->nullable()->after('shipping_cost');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['shipping_name', 'pickup_point']);
        });

        Schema::dropIfExists('shipping_methods');
    }
};
