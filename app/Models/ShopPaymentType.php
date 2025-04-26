<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopPaymentType extends Model
{
    use HasFactory;

    protected $fillable = [        'name',
        'description',
    ];

    public function payments()
    {
        return $this->hasMany(ShopPayment::class, 'payment_type_id');
    }
}
