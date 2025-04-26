<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('shop_payment_types')->insert([
            [
                'name' => 'Redsys',
                'description' => 'Pago con tarjeta bancaria (TPV Virtual Redsys)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Google Pay',
                'description' => 'Pago rápido con Google Pay',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Apple Pay',
                'description' => 'Pago rápido con Apple Pay',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'PayPal',
                'description' => 'Pago seguro con PayPal',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        DB::table('shop_payment_types')->whereIn('name', [
            'Redsys', 'Google Pay', 'Apple Pay', 'PayPal'
        ])->delete();
    }
};
