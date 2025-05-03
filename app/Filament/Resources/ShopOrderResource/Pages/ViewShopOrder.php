<?php

namespace App\Filament\Resources\ShopOrderResource\Pages;

use App\Filament\Resources\ShopOrderResource;
use Filament\Resources\Pages\ViewRecord;

class ViewShopOrder extends ViewRecord
{
    protected static string $resource = ShopOrderResource::class;

    protected function resolveRecord(int|string $key): \Illuminate\Database\Eloquent\Model
    {
        $record = parent::resolveRecord($key)->load([
            'user',
            'items.priceRange',
            'payment.paymentType',
        ]);



        return $record;
    }




}
