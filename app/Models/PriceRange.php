<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceRange extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'price',
        'max_quantity',
        'starts_at',
        'ends_at',
        'visible',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }


}
