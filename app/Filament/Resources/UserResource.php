<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Date;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-m-user';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label("Nombre"),

                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->label('Correo'),

                Forms\Components\Select::make('role_id')
                    ->label('Rol')
                    ->relationship('role', 'name')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('name')->searchable()->label('Nombre'),
                Tables\Columns\TextColumn::make('email')->searchable()->label('Correo'),
                Tables\Columns\TextColumn::make('role.name')
                    ->label('Rol')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'user' => 'gray',
                        'organizer' => 'success',
                        'ticket_checker' => 'warning',
                        default => 'primary',
                    }),
                Tables\Columns\TextColumn::make('created_at')->sortable()->label('Fecha Creacion')->date("d/m/Y H:i"),

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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return "usuario";
    }

    /**
     * @return string|null
     */
    public static function getPluralModelLabel(): string
    {
        return "usuarios";
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Gestión de usuarios';
    }
    public static function getNavigationSort(): ?int
    {
        return 1;
    }


}
