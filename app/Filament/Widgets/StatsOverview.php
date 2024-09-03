<?php

namespace App\Filament\Widgets;

use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
        Stat::make('Usuarios', User::query()->count())
            ->description('Usuarios del sistema')
            ->descriptionIcon('heroicon-m-users')
            ->color('primary'),
        Stat::make('Empleados', Employee::query()->count())
            ->description('Empleados registrados')
            ->descriptionIcon('heroicon-m-user-group')
            ->color('primary'),
        Stat::make('Departamentos', Department::query()->count())
            ->description('Departamentos registrados')
            ->descriptionIcon('heroicon-m-briefcase')
            ->color('primary'),
        ];
    }
}
