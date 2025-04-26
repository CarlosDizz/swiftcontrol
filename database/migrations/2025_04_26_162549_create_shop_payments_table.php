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
        Schema::create('shop_payments', function (Blueprint $table) {
            $table->id();
            $table->uuid('order_id');
            $table->foreign('order_id')->references('id')->on('shop_orders')->onDelete('cascade');
            $table->foreignId('payment_type_id')->constrained('shop_payment_types')->onDelete('cascade');
            $table->enum('status', ['pending', 'successful', 'failed'])->default('pending');
            $table->string('transaction_id')->nullable(); // ID externo de Redsys, PayPal, etc.
            $table->decimal('amount', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_payments');
    }
};
