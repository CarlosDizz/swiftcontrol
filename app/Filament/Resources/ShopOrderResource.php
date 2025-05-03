<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ShopOrderResource\Pages;
use App\Filament\Resources\ShopOrderResource\RelationManagers;
use App\Models\ShopOrder;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Placeholder;

class ShopOrderResource extends Resource
{
    protected static ?string $model = ShopOrder::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Placeholder::make('comprador')
                    ->label('Comprador')
                    ->content(fn ($record) => $record->user->name ?? '—'),
                Select::make('status')
                    ->label('Estado del pedido')
                    ->options([
                        'pending' => 'Pendiente',
                        'paid' => 'Pagado',
                        'cancelled' => 'Cancelado',
                    ])
                    ->disabled(),
                TextInput::make('total_amount')
                    ->label('Importe total')
                    ->disabled(),

                DateTimePicker::make('paid_at')
                    ->label('Pagado el')
                    ->disabled(),

                Section::make('Items del pedido')
                    ->schema([
                        Placeholder::make('items_table')
                            ->content(fn ($record) => view('filament.components.order-items', ['items' => $record->items]))
                    ]),

                Section::make('Pago')
                    ->schema([
                        Placeholder::make('metodo_pago')
                            ->label('Método de pago')
                            ->content(fn ($record) => $record->payment->paymentType->name ?? '—'),

                        Placeholder::make('estado_pago')
                            ->label('Estado del pago')
                            ->content(fn ($record) => $record->payment->status ?? '—'),

                        Placeholder::make('transaction_id')
                            ->label('Transacción')
                            ->content(fn ($record) => $record->payment->transaction_id ?? '—'),
                    ])


            ]);
    }



    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->label('Comprador'),
                Tables\Columns\TextColumn::make('status')->badge(),
                Tables\Columns\TextColumn::make('total_amount')->money('EUR')->label('Importe total'),
                Tables\Columns\TextColumn::make('paid_at')->label('Pagado el'),
                Tables\Columns\TextColumn::make('created_at')->label('Creado'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([]); // sin acciones masivas
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
            'index' => Pages\ListShopOrders::route('/'),
            'view' => Pages\ViewShopOrder::route('/{record}'),
        ];
    }
    public static function getModelLabel(): string
    {
        return 'Pedido';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Pedidos';
    }


}
