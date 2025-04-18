<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'date',
        'capacity',
        'info',
        'poster_file_id'
    ];

    protected $casts = [
        'info' => 'array',
    ];


    public function priceRanges()
    {
        return $this->hasMany(\App\Models\PriceRange::class);
    }



    public function poster()
    {
        return $this->belongsTo(MediaFile::class, 'poster_file_id');
    }


}


