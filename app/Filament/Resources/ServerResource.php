<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServerResource\Pages;
use App\Filament\Resources\ServerResource\RelationManagers;
use App\Filament\Resources\ServerResource\RelationManagers\UrlsRelationManager;
use App\Models\City;
use App\Models\Country;
use App\Models\Server;
use App\Models\State;
use DeepCopy\Filter\Filter;
use Filament\Forms;
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
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\SelectColumn;

class ServerResource extends Resource
{
    protected static ?string $model = Server::class;
    protected static ?string $navigationLabel = 'Servidores';
    protected static ?string $modelLabel = 'Servidores';
    protected static ?string $navigationIcon = 'heroicon-o-server-stack';
    protected static ?string $navigationGroup = 'Administración de Servidores';
    protected static ?int $navigationSort = 5;
    
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
                Section::make('Sección servidor')
                    ->description('Desde aquí podrás seleccionar y digitar la información requrida para crear el servidor')
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
                        TextInput::make('operating_system')
                            ->label('Sistema operativo')
                            ->required()
                            ->maxLength(100),
                        TextInput::make('ip')
                            ->label('IP')
                            ->required()
                            ->maxLength(20)
                            ->unique(ignoreRecord: true),
                        TextInput::make('brand')
                            ->label('Marca')
                            ->required()
                            ->maxLength(100),
                        TextInput::make('name_plate')
                            ->label('Placa / Activo')
                            ->required()
                            ->maxLength(50)
                            ->unique(ignoreRecord: true),
                        TextInput::make('serial_number')
                            ->label('Número de serie')
                            ->required()
                            ->maxLength(50)
                            ->unique(ignoreRecord: true),
                        TextInput::make('ram_capacity')
                            ->label('Capacidad de RAM')
                            ->required()
                            ->maxLength(20),
                        TextInput::make('processor')
                            ->label('Procesador')
                            ->required()
                            ->maxLength(100),
                        TextInput::make('stora_capacity')
                             ->label('Almacenamiento')
                            ->required()
                            ->maxLength(50),
                        Select::make('type')
                            ->label('Tipo')
                            ->options([
                                'virtual' => 'Virtual',
                                'physical' => 'Físico',
                            ])
                            ->required(),
                        DatePicker::make('date_installation')
                            ->label('Fecha de entrega')
                            ->required(),
                        Toggle::make('active')
                            ->label('Estado')
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
                TextColumn::make('country.name')
                    ->label('País')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('state.name')
                    ->label('Estado')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('city.name')
                    ->label('Ciudad')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('department.name')
                    ->label('Departamento')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('name')
                    ->label('Nombre')
                    ->copyable()
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('operating_system')
                    ->label('Sistema Operativo')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('ip')
                    ->label('IP')
                    ->copyable()
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('brand')
                    ->label('Marca')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('name_plate')
                    ->label('Placa/Activo')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('serial_number')
                    ->label('Número de serie')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('ram_capacity')
                    ->label('RAM')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('processor')
                    ->label('Procesador')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('stora_capacity')
                    ->label('Almacenamiento')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                SelectColumn::make('type')
                    ->label('Tipo')
                    ->options([
                        'virtual' => 'Virtual',
                        'physical' => 'Físico',
                    ])
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->selectablePlaceholder(false)
                    ->disabled(),
                TextColumn::make('date_installation')
                    ->label('Fecha de entrega')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                Tables\Actions\Action::make('createPDF')
                    ->label('PDF')
                    ->color('danger')
                    ->icon('heroicon-o-document-arrow-down')
                    ->requiresConfirmation()
                    ->url(fn(Server $record) => route('pdf.server', $record))
                    ->openUrlInNewTab(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    ExportBulkAction::make()->exports([
                        ExcelExport::make('Tabla')->fromTable()
                            ->askForFilename('Nombre de archivo')
                            ->askForWriterType(),
                    ]),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            UrlsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServers::route('/'),
            'create' => Pages\CreateServer::route('/create'),
            'view' => Pages\ViewServer::route('/{record}'),
            'edit' => Pages\EditServer::route('/{record}/edit'),
        ];
    }
}
