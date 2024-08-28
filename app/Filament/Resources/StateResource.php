<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StateResource\Pages;
use App\Filament\Resources\StateResource\RelationManagers;
use App\Models\State;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StateResource extends Resource
{
    protected static ?string $model = State::class;
    protected static ?string $navigationLabel = 'Estados';
    protected static ?string $modelLabel = 'Estados';
    protected static ?string $navigationGroup = 'Administración del sistema';
    protected static ?string $navigationIcon = 'heroicon-o-flag';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('country_id')
                    ->required()
                    ->label('País')
                    ->relationship(name: 'country', titleAttribute: 'name'),
                TextInput::make('state_code')
                    ->required()
                    ->label('Código de área / Siglas')
                    ->maxLength(3),
                TextInput::make('name')
                    ->required()
                    ->label('Nombre')
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
                TextColumn::make('name')
                    ->label('Nombre')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('state_code')
                    ->sortable()
                    ->label('Cód área / Siglas')
                    ->searchable(),
                TextColumn::make('country.name')
                    ->sortable()
                    ->label('País')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->sortable()
                    ->label('Fecha de creación')
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStates::route('/'),
            'create' => Pages\CreateState::route('/create'),
            'view' => Pages\ViewState::route('/{record}'),
            'edit' => Pages\EditState::route('/{record}/edit'),
        ];
    }
}
