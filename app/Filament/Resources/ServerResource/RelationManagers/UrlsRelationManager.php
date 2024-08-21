<?php

namespace App\Filament\Resources\ServerResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UrlsRelationManager extends RelationManager
{
    protected static string $relationship = 'urls';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('server_id')
                    ->relationship('server', 'name')
                    ->label('Nombre Servidor')
                    ->required(),
                TextInput::make('url_principal')
                    ->label('Url Principal')
                    ->url()
                    ->suffixIcon('heroicon-m-globe-alt')
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                TextInput::make('url_contingency')
                    ->label('Url Contingencia')
                    ->url()
                    ->suffixIcon('heroicon-m-globe-alt')
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                DateTimePicker::make('date_deployment')
                    ->label('Fecha de despliegue')
                    ->required(),
                Toggle::make('active')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('url_principal')
            ->columns([
                TextColumn::make('server.name')
                    ->label('Nombre de Servidor')
                    ->numeric()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('url_principal')
                    ->label('Url principal')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('url_contingency')
                    ->label('Url contingencia')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('date_deployment')
                    ->label('Fecha de despliegue')
                    ->dateTime()
                    ->sortable(),
                IconColumn::make('active')
                    ->label('Estado')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
