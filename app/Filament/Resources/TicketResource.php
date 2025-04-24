<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TicketResource\Pages;
use App\Models\PriceRange;
use App\Models\Ticket;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\DateTimeColumn;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('event_id')
                    ->relationship('event', 'name')
                    ->required()
                    ->label('Evento'),

                Select::make('event_price_range_id')
                    ->relationship('priceRange', 'type')
                    ->required()
                    ->label('Tipo de entrada'),

                Select::make('buyer_id')
                    ->relationship('buyer', 'name')
                    ->searchable()
                    ->label('Comprador')
                    ->required(),

                DateTimePicker::make('cheked_in')
                    ->label('Fecha de uso')
                    ->nullable()
                    ->disabled(),

                TextInput::make('transfer_email')
                    ->label('Correo de destino (transferencia)')
                    ->default(fn ($record) => $record?->data['transferred']['to_email'] ?? null)
                    ->disabled()
                    //->visible(fn ($record) => filled($record?->data['transferred']['to_email'] ?? null))
                    ,

                TextInput::make('token')
                    ->disabled()
                    ->dehydrated(false)
                    ->label('Token (generado automáticamente)'),

                Section::make('Estado de la entrada')
                    ->description('Información de uso y transferencias')
                    ->schema([
                        Textarea::make('data_summary')
                            ->label('Historial')
                            ->disabled()
                            ->rows(8)
                            ->default(fn ($record) => $record ? self::getFormattedTicketData($record) : ''),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('event.name')->label('Evento'),
                TextColumn::make('priceRange.type')->label('Tipo de entrada'),
                TextColumn::make('buyer.name')->label('Comprador'),
                TextColumn::make('owner.name')->label('Propietario'),
                TextColumn::make('used_at')->label('Usada')->dateTime('d/m/Y H:i')->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTickets::route('/'),
            'create' => Pages\CreateTicket::route('/create'),
            'edit' => Pages\EditTicket::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return 'Entrada';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Entradas';
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function priceRange()
    {
        return $this->belongsTo(PriceRange::class, 'event_price_range_id');
    }

    public static function getFormattedTicketData($ticket): string
    {
        $data = $ticket->data ?? [];
        $lines = [];

        if (isset($data['checked_in'])) {
            $lines[] = "✅ Usada por usuario ID {$data['checked_in']['user_id']} el {$data['checked_in']['timestamp']}";
        } else {
            $lines[] = "❌ No ha sido usada aún.";
        }

        if (isset($data['transferred'])) {
            $lines[] = "🎁 Transferida a {$data['transferred']['to_email']} el {$data['transferred']['timestamp']}";
        }

        if (isset($data['history'])) {
            $lines[] = "📜 Historial:";
            foreach ($data['history'] as $event) {
                $timestamp = $event['timestamp'] ?? 'sin fecha';
                if ($event['type'] === 'transfer') {
                    $lines[] = "- 🔁 Transferida a {$event['to_email']} el $timestamp";
                } elseif ($event['type'] === 'checkin') {
                    $lines[] = "- ✅ Usada por usuario ID {$event['by']} el $timestamp";
                }
            }
        }

        return implode("\n", $lines);
    }
}
