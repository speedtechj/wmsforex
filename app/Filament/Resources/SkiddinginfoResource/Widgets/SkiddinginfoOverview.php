<?php

namespace App\Filament\Resources\SkiddinginfoResource\Widgets;

use App\Filament\Resources\SkiddinginfoResource\Pages\ListSkiddinginfos;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use PHPUnit\Framework\Constraint\IsTrue;

class SkiddinginfoOverview extends BaseWidget
{
    use InteractsWithPageTable;
    protected function getTablePage(): string 
    {
        return ListSkiddinginfos::class;
    }
    protected function getStats(): array
    {
        
       $totalnotencoded = $this->getPageTableQuery()->where('is_encode',false)->count() ?? 0;
       $totalencoded = $this->getPageTableQuery()->where('is_encode',true)->count() ?? 0;
        return [
            Stat::make('Total Box Not Encoded',  $totalnotencoded),
            Stat::make('Total Box Encoded',  $totalencoded),
           
        ];
    }
}
