<?php

namespace App\Filament\Resources\SkidsummaryResource\Widgets;

use App\Models\Batch;
use App\Models\Skiddinginfo;
use Illuminate\Support\Number;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use App\Filament\Resources\SkidsummaryResource\Pages\ListSkidsummaries;

class SummaryStatsOverview extends BaseWidget
{
    use InteractsWithPageTable;
    protected function getTablePage(): string 
    {
        return ListSkidsummaries::class;
    }
    protected function getStats(): array
    {
        $batch_current = $this->getPageTableQuery()->get()->first()->batch_id ?? Batch::currentbatch();
        $totalbox = Skiddinginfo::where('batch_id', $batch_current)->count();
       $totalweight = $this->getPageTableQuery()->sum('weight');
      
        return [
            
            Stat::make('Total Box',  $totalbox),
            Stat::make('Total Weight', Number::format($totalweight)),
            
        ];
    }
}
