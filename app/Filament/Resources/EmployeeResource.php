<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\Pages;
use App\Filament\Resources\EmployeeResource\RelationManagers;
use App\Models\City;
use App\Models\Country;
use App\Models\Employee;
use App\Models\State;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
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

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;
    protected static ?string $navigationLabel = 'Empleados';
    protected static ?string $modelLabel = 'Empleados';
    protected static ?string $navigationGroup = 'Administración de Personal';
    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Sección personal')
                    ->description('Desde aquí podrás seleccionar y digitar la información requrida para crear el personal')
                    ->schema([
                        Select::make('department_id')
                            ->label('Departamento')
                            ->relationship(name: 'department', titleAttribute: 'name')
                            ->required(),
                        TextInput::make('name')
                            ->label('Nombre y Apellidos')
                            ->required()
                            ->maxLength(100),
                        TextInput::make('address')
                            ->label('Dirección')
                            ->required()
                            ->maxLength(100),
                        TextInput::make('phone_number')
                            ->label('Número de Celular')
                            ->required()
                            ->numeric()
                            ->maxLength(10),
                        TextInput::make('email')
                            ->label('Email')
                            ->required()
                            ->email()
                            ->maxLength(100),
                        TextInput::make('zip_code')
                            ->label('Código Postal')
                            ->numeric()
                            ->required()
                            ->maxLength(10),
                        DatePicker::make('birth_date')
                            ->label('Fecha de Cumpleaños')
                            ->required(),
                        Toggle::make('active')
                            ->label('Estado Actual')
                            ->required(),

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
                TextColumn::make('id')
                    ->label('ID'),
                TextColumn::make('name')
                    ->label('Nombre y Apellidos')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('phone_number')
                    ->label('Celular')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('address')
                    ->label('Direción')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault : true),
                TextColumn::make('email')
                    ->label('Email')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('zip_code')
                    ->label('Código Postal')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault : true),
                TextColumn::make('birth_date')
                    ->label('Fecha de Cumpleaños')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault : true),
                TextColumn::make('department.name')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                IconColumn::make('active')
                    ->boolean(),
                TextColumn::make('country.name')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault : true),
                TextColumn::make('state.name')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault : true),
                TextColumn::make('city.name')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
            ])->defaultSort('id')
            ->filters([
                SelectFilter::make('Departamento')
                    ->relationship('department', 'name')
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
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'view' => Pages\ViewEmployee::route('/{record}'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}
