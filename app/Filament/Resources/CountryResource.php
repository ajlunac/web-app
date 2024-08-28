<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CountryResource\Pages;
use App\Filament\Resources\CountryResource\RelationManagers;
use App\Filament\Resources\CountryResource\RelationManagers\StatesRelationManager;
use App\Models\Country;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CountryResource extends Resource
{
    protected static ?string $model = Country::class;
    protected static ?string $navigationLabel = 'Paises';
    protected static ?string $modelLabel = 'Paises';
    protected static ?string $navigationGroup = 'Administración del sistema';
    protected static ?string $navigationIcon = 'heroicon-o-map';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('country_code')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->label('Código de país')
                    ->maxLength(3),
                TextInput::make('area_code')
                    ->required()
                    ->label('Código de área / Telefónico')
                    ->numeric()
                    ->unique(ignoreRecord: true)
                    ->maxLength(3),
                TextInput::make('name')
                    ->required()
                    ->label('Nombre')
                    ->unique(ignoreRecord: true)
                    ->maxLength(100),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('country_code')
                    ->label('Código de país')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('area_code')
                    ->label('Cód área / Telefónico')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Fecha de creación')
                    ->sortable()
                    ->dateTime(),
                
            ])->defaultSort('id')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            // StatesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCountries::route('/'),
            'create' => Pages\CreateCountry::route('/create'),
            'view' => Pages\ViewCountry::route('/{record}'),
            'edit' => Pages\EditCountry::route('/{record}/edit'),
        ];
    }
}
