<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TestResource\Pages;
use App\Filament\Resources\TestResource\RelationManagers;
use App\Models\City;
use App\Models\Country;
use App\Models\State;
use App\Models\Test;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TestResource extends Resource
{
    protected static ?string $model = Test::class;
    protected static ?string $navigationLabel = 'Pruebas';
    protected static ?string $modelLabel = 'Pruebas';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Pruebas';
    protected static ?int $navigationSort = 7;
    
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::count() > 10 ? 'warning' : 'danger';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Section::make('Sección Pruebas')
                    ->description('Desde aquí podrás seleccionar y digitar la información requrida para crear una prueba')
                    ->schema([
                        Select::make('department_id')
                            ->relationship('department', 'name')
                            ->label('Departamento')
                            ->required(),
                        TextInput::make('name')
                            ->label('Nombre')
                            ->required()
                            ->maxLength(100)
                            ->unique(ignoreRecord: true),
                        Select::make('type')
                            ->label('Tipo')
                            ->options([
                                'virtual' => 'Virtual',
                                'physical' => 'Físico',
                            ])
                            ->required(),
                        DateTimePicker::make('date_installation')
                            ->label('Fecha de Instalación')
                            ->required(),
                        Toggle::make('active')
                            ->label('Estado')
                            ->required(),
                        Textarea::make('comment')
                            ->required()
                            ->label('Comentario')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ])->columns(3),

                Section::make('Sección regional')
                ->description('Desde aquí podrás hacer selección de las opciones regionales')
                ->schema([
                    Select::make('country_id')
                        ->label('Paises')
                        ->options(Country::all()->pluck('name', 'id')->toArray())
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(fn (callable $set) => $set('state_id', null)),

                    Select::make('state_id')
                        ->label('Estados')
                        ->options(function (callable $get){
                            $country = Country::find($get('country_id'));
                            if(!$country){
                                return State::all()->pluck('name', 'id');
                            }
                            return $country->states->pluck('name', 'id');
                        })
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(fn (callable $set) => $set('city_id', null)),

                    Select::make('city_id')
                        ->label('Ciudades')
                        ->options(function (callable $get){
                            $state = State::find($get('state_id'));
                            if(!$state){
                                return City::all()->pluck('name', 'id');
                            }
                            return $state->cities->pluck('name', 'id');
                        })
                        ->required()
                        ->reactive(),

                ])->columns(3)

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('country.name')
                    ->label('País')
                    ->sortable(),
                Tables\Columns\TextColumn::make('state.name')
                    ->label('Estado')
                    ->sortable(),
                Tables\Columns\TextColumn::make('city.name')
                    ->label('Ciudad')
                    ->sortable(),
                Tables\Columns\TextColumn::make('department.name')
                    ->label('Departamento')
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->copyable()
                    ->searchable(),
                Tables\Columns\SelectColumn::make('type')
                    ->label('Tipo')
                    ->options([
                        'virtual' => 'Virtual',
                        'physical' => 'Físico',
                    ])
                    ->selectablePlaceholder(false)
                    ->disabled(),
                Tables\Columns\TextColumn::make('date_installation')
                    ->label('Fecha de Instalación')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\IconColumn::make('active')
                    ->label('Estado')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha de Creación')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])->defaultSort('name')
            ->filters([
                SelectFilter::make('Departamentos')
                    ->relationship('department', 'name')
                    ->searchable()
                    ->preload()
                    ->indicator('Departamentos'),
                SelectFilter::make('Ciudades')
                    ->relationship('city', 'name')
                    ->searchable()
                    ->indicator('Ciudades')
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Prueba eliminada.')
                            ->body('La prueba se ha eliminado de manera correcta.')
                    ),
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
            'index' => Pages\ListTests::route('/'),
            'create' => Pages\CreateTest::route('/create'),
            'view' => Pages\ViewTest::route('/{record}'),
            'edit' => Pages\EditTest::route('/{record}/edit'),
        ];
    }
}
