<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'payment_type_id',
        'status',
        'transaction_id',
        'amount',
    ];

    public function order()
    {
        return $this->belongsTo(ShopOrder::class, 'order_id');
    }

    public function paymentType()
    {
        return $this->belongsTo(ShopPaymentType::class, 'payment_type_id');
    }
}
