<?php

namespace App\Filament\Resources\UsersResource\Widgets;

use App\Models\Event;
use App\Models\Role;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    public function getStats(): array
    {
        return [
            Stat::make('Usuarios registrados', User::query()->count()),
            Stat::make('Roles definidos', Role::query()->count()),
            Stat::make('Conciertos', Event::query()->count())
        ];
    }

}
