<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shop_payment_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Ej: Redsys, PayPal, Google Pay
            $table->string('description')->nullable(); // Opcional
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_payment_types');
    }
};
