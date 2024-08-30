<?php

namespace App\Filament\Resources\TestResource\Pages;

use App\Filament\Resources\TestResource;
use App\Models\Test;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListTests extends ListRecords
{
    protected static string $resource = TestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return[
            'Todos' => Tab::make()
                ->badge(Test::query()->count()),
            'Hoy' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('date_installation', '>=', now()->today()))
                ->badge(Test::query()->where('date_installation', '>=', now()->today())->count()),
            'Esta semana' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('date_installation', '>=', now()->subWeek()))
                ->badge(Test::query()->where('date_installation', '>=', now()->subWeek())->count()),
            'Este mes' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('date_installation', '>=', now()->subMonth()))
                ->badge(Test::query()->where('date_installation', '>=', now()->subMonth())->count()),
            'Este año' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('date_installation', '>=', now()->subYear()))
                ->badge(Test::query()->where('date_installation', '>=', now()->subYear())->count()),
        ];
    }
}
