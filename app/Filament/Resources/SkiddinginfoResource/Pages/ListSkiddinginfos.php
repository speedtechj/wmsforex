<?php

namespace App\Filament\Resources\SkiddinginfoResource\Pages;

use App\Models\Batch;
use Filament\Actions;
use App\Models\Skiddinginfo;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Pages\ListRecords\Tab;
use App\Filament\Resources\SkiddinginfoResource;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use App\Filament\Resources\SkiddinginfoResource\Widgets\SkiddinginfoOverview;
use Illuminate\Contracts\Pagination\Paginator;

 
class ListSkiddinginfos extends ListRecords
{
    use ExposesTableToWidgets;
    protected static string $resource = SkiddinginfoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            
        ];
    }
//     public function getTabs(): array {
//         return [
//             'All' => Tab::make(),
//             'Curent Batch' => Tab::make()
//             ->modifyQueryUsing(function (Builder $query) {
//                 $query->where('batch_id', Batch::currentbatch());
//             })->badge('Total Boxes'. ' '. Skiddinginfo::query()->where('batch_id', Batch::currentbatch())->count()),
//         ];
        
//     }
//     public function getDefaultActiveTab(): string | int | null
// {
//     return 'All';
// }
protected function getHeaderWidgets(): array
    {
        return [
            SkiddinginfoOverview::class,
           
        ];
    }
//     protected function paginateTableQuery(Builder $query): Paginator
// {
//     return $query->simplePaginate(($this->getTableRecordsPerPage() === 'all') ? $query->count() : $this->getTableRecordsPerPage());
// }
protected function paginateTableQuery(Builder $query): Paginator
{
    return $query->fastPaginate($this->getTableRecordsPerPage());
}
}
