<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TicketResource\Pages;
use App\Filament\Resources\TicketResource\RelationManagers;
use App\Models\PriceRange;
use App\Models\Ticket;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\DateTimeColumn;

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
                    ->required()->label('Evento'),

                Select::make('event_price_range_id')
                    ->relationship('priceRange', 'type')
                    ->required()->label('Tipo de entrada'),

                Select::make('buyer_id')
                    ->relationship('buyer', 'name')
                    ->searchable()
                    ->label('Comprador')
                    ->required(),


                DateTimePicker::make('cheked_in')
                    ->label('Fecha de uso')
                    ->nullable()
                ->disabled(),

                TextInput::make('token')
                    ->disabled()
                    ->dehydrated(false)
                    ->label('Token (generado automáticamente)'),
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
                TextColumn::make('used_at')->label('Usada')->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
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
        return [
            //
        ];
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

    // app/Models/Ticket.php

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


}
