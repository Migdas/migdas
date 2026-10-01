<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->string('number')->unique();

            $table->string('status')->default('new');
            $table->string('payment_status')->default('unpaid');

            $table->string('first_name');
            $table->string('last_name');

            $table->string('email');
            $table->string('phone');

            $table->string('street');
            $table->string('building_number');
            $table->string('apartment_number')->nullable();

            $table->string('postal_code', 10);
            $table->string('city');

            $table->string('shipping_method');
            $table->decimal('shipping_cost', 10, 2)->default(0);

            $table->decimal('products_total', 10, 2);
            $table->decimal('total', 10, 2);

            $table->text('customer_note')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
