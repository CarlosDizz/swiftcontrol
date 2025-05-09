<?php

namespace App\Filament\Widgets;

use App\Models\Event;
use App\Models\Ticket;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class TicketSalesChart extends ChartWidget
{
    protected static ?string $heading = 'Ventas de entradas por evento';
    protected static ?string $maxHeight = '400px';

    public function getColumnSpan(): int|string|array
    {
        return 2;
    }

    protected function getData(): array
    {
        $activeEventIds = Event::where('date', '>=', now())->pluck('id');

        $data = Ticket::whereIn('event_id', $activeEventIds)
            ->where('created_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(created_at) as date, event_id, COUNT(*) as total')
            ->groupBy('date', 'event_id')
            ->orderBy('date')
            ->get();

        $events = Event::whereIn('id', $activeEventIds)->pluck('name', 'id');

        $datasets = [];

        foreach ($events as $eventId => $name) {
            $eventData = $data->where('event_id', $eventId);

            $datasets[] = [
                'label' => $name,
                'data' => $eventData->map(fn ($d) => [
                    'x' => $d->date,
                    'y' => $d->total,
                ])->values()->toArray(),
            ];
        }

        return [
            'datasets' => $datasets,
        ];
    }

    protected function getType(): string
    {
        return 'line'; // o 'bar'
    }
}
