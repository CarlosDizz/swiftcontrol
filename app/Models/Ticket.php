<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


class Ticket extends Model
{
    protected $fillable = [
        'event_id',
        'event_price_range_id',
        'buyer_id',
        'owner_id',
        'used_at',
        'token',
    ];

    protected $casts = [
        'used_at' => 'datetime',
        'data' => 'array',
    ];

    protected static function booted()
    {
        static::creating(function ($ticket) {
            $ticket->token = (string) Str::uuid();

            $qrImage = QrCode::format('svg')->size(3000)->generate($ticket->token);

            MediaFile::storeFromContent($qrImage, "{$ticket->token}.svg", $ticket->token);
        });

    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function priceRange(): BelongsTo
    {
        return $this->belongsTo(PriceRange::class, 'event_price_range_id');
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }




}

