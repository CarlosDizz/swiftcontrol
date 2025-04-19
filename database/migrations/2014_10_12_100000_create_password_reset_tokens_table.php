<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('event_id');
            $table->uuid('price_range_id');
            $table->uuid('buyer_id');
            $table->uuid('owner_id')->nullable();
            $table->string('transferred_to_email')->nullable();
            $table->boolean('is_checked_in')->default(false);
            $table->string('qr_token')->unique();
            $table->uuid('media_file_id')->nullable(); // QR en imagen, si decides guardarlo
            $table->timestamps();

            // Relaciones
            $table->foreign('event_id')->references('id')->on('events')->cascadeOnDelete();
            $table->foreign('price_range_id')->references('id')->on('price_ranges')->cascadeOnDelete();
            $table->foreign('buyer_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('owner_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('media_file_id')->references('id')->on('media_files')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};

