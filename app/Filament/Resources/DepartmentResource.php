<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DepartmentResource\Pages;
use App\Filament\Resources\DepartmentResource\RelationManagers;
use App\Models\Department;
use App\Models\Employee;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DepartmentResource extends Resource
{
    protected static ?string $model = Department::class;
    protected static ?string $navigationLabel = 'Departamentos';
    protected static ?string $modelLabel = 'Departamentos';
    protected static ?string $navigationGroup = 'Administración de Personal';
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->label('Nombre')
                    ->maxLength(100),
                TextInput::make('email')
                    ->required()
                    ->email()
                    ->maxLength(100),
                TextInput::make('extension_phone')
                    ->required()
                    ->label('Extensión')
                    ->numeric()
                    ->maxLength(10),
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
                    ->sortable()
                    ->label('Nombre')
                    ->searchable(),
                TextColumn::make('employees_count')->counts('employees')
                    ->label('Empleados por departamentos'),
                TextColumn::make('email')
                    ->sortable()
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('extension_phone')
                    ->sortable()
                    ->label('Extensión')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->sortable()
                    ->label('Fecha de creación')
                    ->searchable(),

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
            'index' => Pages\ListDepartments::route('/'),
            'create' => Pages\CreateDepartment::route('/create'),
            'view' => Pages\ViewDepartment::route('/{record}'),
            'edit' => Pages\EditDepartment::route('/{record}/edit'),
        ];
    }
}
