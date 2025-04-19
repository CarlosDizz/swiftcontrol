<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            // Eliminar primero las claves foráneas si existen
            $table->dropForeign(['buyer_id']);
            $table->dropForeign(['owner_id']);

            // Cambiar tipo de columnas a unsignedBigInteger
            $table->dropColumn('buyer_id');
            $table->dropColumn('owner_id');

            $table->unsignedBigInteger('buyer_id')->nullable()->after('price_range_id');
            $table->unsignedBigInteger('owner_id')->nullable()->after('buyer_id');

            // Volver a crear las claves foráneas
            $table->foreign('buyer_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('owner_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropForeign(['buyer_id']);
            $table->dropForeign(['owner_id']);

            $table->dropColumn('buyer_id');
            $table->dropColumn('owner_id');

            $table->uuid('buyer_id')->nullable();
            $table->uuid('owner_id')->nullable();
        });
    }
};
