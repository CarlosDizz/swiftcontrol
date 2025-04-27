<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ShopOrder;
use App\Models\ShopOrderItem;
use App\Models\PriceRange;

class OrderController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.event_price_range_id' => 'required|exists:price_ranges,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        return DB::transaction(function () use ($request) {
            $total = 0;

            // Crear pedido
            $order = ShopOrder::create([
                'user_id' => auth()->id(),
                'status' => 'pending',
                'total_amount' => 0,
            ]);

            // Insertar los items
            foreach ($request->items as $item) {
                $priceRange = PriceRange::findOrFail($item['event_price_range_id']);
                $unitPrice = $priceRange->price;
                $quantity = $item['quantity'];
                $subtotal = $unitPrice * $quantity;

                ShopOrderItem::create([
                    'order_id' => $order->id,
                    'event_price_range_id' => $priceRange->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                ]);

                $total += $subtotal;
            }


            $order->update([
                'total_amount' => $total,
            ]);

            return response()->json([
                'order_id' => $order->id,
                'total' => $total,
                'message' => 'Pedido creado correctamente.',
            ]);
        });
    }
    public function mine()
    {
        $orders = ShopOrder::with(['items.priceRange', 'payment'])
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($orders);
    }

}
