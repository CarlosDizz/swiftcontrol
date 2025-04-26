<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'event_price_range_id',
        'quantity',
        'unit_price',
    ];

    public function order()
    {
        return $this->belongsTo(ShopOrder::class, 'order_id');
    }

    public function priceRange()
    {
        return $this->belongsTo(PriceRange::class, 'event_price_range_id');
    }
}
