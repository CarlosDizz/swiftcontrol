<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ShopOrder;
use App\Models\ShopPayment;
use App\Models\Ticket;

class PaymentController extends Controller
{
    public function confirm(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:shop_orders,id',
            'payment_type_id' => 'required|exists:shop_payment_types,id',
            'transaction_id' => 'nullable|string',
            'status' => 'required|in:successful,failed',
        ]);

        return DB::transaction(function () use ($request) {
            $order = ShopOrder::findOrFail($request->order_id);

            // Crear registro de pago
            $payment = ShopPayment::create([
                'order_id' => $order->id,
                'payment_type_id' => $request->payment_type_id,
                'status' => $request->status,
                'transaction_id' => $request->transaction_id,
                'amount' => $order->total_amount,
            ]);

            if ($request->status === 'successful') {
                // Marcar pedido como pagado
                $order->update([
                    'status' => 'paid',
                    'paid_at' => now(),
                ]);

                // Crear tickets a partir de los items
                foreach ($order->items as $item) {
                    for ($i = 0; $i < $item->quantity; $i++) {
                        Ticket::create([
                            'buyer_id' => $order->user_id,
                            'event_id' => $item->priceRange->event_id,
                            'event_price_range_id' => $item->event_price_range_id,
                            'token' => \Illuminate\Support\Str::uuid(),
                            'data' => [],
                        ]);
                    }
                }

                return response()->json([
                    'message' => 'Pago confirmado. Entradas generadas.',
                ]);
            } else {
                // Si el pago ha fallado
                $order->update([
                    'status' => 'cancelled',
                ]);

                return response()->json([
                    'message' => 'Pago fallido. Pedido cancelado.',
                ], 400);
            }
        });
    }
}
