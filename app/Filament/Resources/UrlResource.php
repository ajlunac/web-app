<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UrlResource\Pages;
use App\Filament\Resources\UrlResource\RelationManagers;
use App\Models\Url;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UrlResource extends Resource
{
    protected static ?string $model = Url::class;
    protected static ?string $navigationLabel = 'Urls';
    protected static ?string $modelLabel = 'Urls';
    protected static ?string $navigationIcon = 'heroicon-o-globe-americas';
    protected static ?string $navigationGroup = 'Administración de Servidores';
    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
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
                    ->maxLength(150),
                TextInput::make('url_contingency')
                    ->label('Url Contingencia')
                    ->url()
                    ->suffixIcon('heroicon-m-globe-alt')
                    ->unique(ignoreRecord: true)
                    ->maxLength(150),
                DatePicker::make('date_deployment')
                    ->label('Fecha de despliegue')
                    ->required(),
                Toggle::make('active')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('server.name')
                    ->label('Nombre de Servidor')
                    ->copyable()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('url_principal')
                    ->label('Url principal')
                    ->copyable()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('url_contingency')
                    ->label('Url contingencia')
                    ->copyable()
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
            ])->defaultSort('server.name')
            ->filters([
                SelectFilter::make('Servidor')
                    ->relationship('server', 'name')
                    ->searchable()
                    ->preload(),
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
            'index' => Pages\ListUrls::route('/'),
            'create' => Pages\CreateUrl::route('/create'),
            'view' => Pages\ViewUrl::route('/{record}'),
            'edit' => Pages\EditUrl::route('/{record}/edit'),
        ];
    }
}
