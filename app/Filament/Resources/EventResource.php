<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Filament\Resources\EventResource\RelationManagers;
use App\Models\Event;
use App\Models\MediaFile;
use Faker\Core\Uuid;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\HasManyRepeater;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Illuminate\Support\Str;
use App\Models\MediaFile as Media;
use Filament\Forms\Components\ViewField;

use Filament\Forms\Components\View;



class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-speaker-wave';
    }
    public static function getNavigationLabel(): string
    {
        return 'Eventos / Conciertos';
    }
    public static function getModelLabel(): string
    {
        return 'Evento / Concierto';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Eventos / Conciertos';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Información del evento')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('name')
                                ->label('Evento')
                                ->required()
                                ->maxLength(255),

                            DateTimePicker::make('date')
                                ->label('Fecha del evento')
                                ->required(),
                        ]),


                        TextInput::make('capacity')
                            ->label('Aforo máximo')
                            ->numeric()
                            ->required(),

                        Repeater::make('info')
                            ->label('Información del evento')
                            ->schema([
                                TextInput::make('name')->label('Nombre'),
                                TextInput::make('detalle')->label('Detalle'),
                            ])
                            ->defaultItems(0)
                            ->addActionLabel('Añadir ítem')
                            ->columns(2)
                            ->helperText('Puedes añadir bandas, detalles u otra información que desees')
                            ->default([])
                        ,
                    ]),
                Repeater::make('priceRanges')
                    ->schema([
                        TextInput::make('type')->required()->label('Tipo de entrada'),
                        TextInput::make('price')->numeric()->required()->label('Precio'),
                        TextInput::make('max_quantity')->numeric()->required()->label('Máximo de entradas'),
                        DateTimePicker::make('starts_at')->required()->label('Inicio del Rango'),
                        DateTimePicker::make('ends_at')->required()->label('Fin del Rango'),
                        Toggle::make('visible')->label('¿Visible?')->default(true),
                    ])
                    ->columns(2)
                    ->defaultItems(1)
                    ->dehydrated(false)
                ->visibleOn("edit")
                ->relationship('priceRanges')->label('Precios entrada'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('date')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i') // 👈 formato bonito

                    ->sortable(),

                Tables\Columns\TextColumn::make('capacity')
                    ->label('Aforo'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }
}
