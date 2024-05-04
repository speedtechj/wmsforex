<?php

namespace App\Filament\Resources\SkidsummaryResource\Pages;



use App\Filament\Resources\SkidsummaryResource\Widgets\Boxsummary;
use Filament\Actions;
use Illuminate\Contracts\View\View;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\SkidsummaryResource;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use App\Filament\Resources\SkidsummaryResource\Widgets\SummaryStatsOverview;

class ListSkidsummaries extends ListRecords
{
    use ExposesTableToWidgets;
    protected static string $resource = SkidsummaryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
    protected function getHeaderWidgets(): array
    {
        return [
            SummaryStatsOverview::class,
            Boxsummary::class,
        ];
    }
}
