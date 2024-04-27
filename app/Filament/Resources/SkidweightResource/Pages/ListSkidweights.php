<?php

namespace App\Filament\Resources\SkidweightResource\Pages;

use App\Models\Batch;
use Filament\Actions;
use App\Models\Skidweight;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;
use App\Filament\Resources\SkidweightResource;
use Illuminate\Contracts\Database\Eloquent\Builder;

class ListSkidweights extends ListRecords
{
    protected static string $resource = SkidweightResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    public function getTabs(): array {
        return [
            'Curent Batch' => Tab::make()
            ->modifyQueryUsing(function (Builder $query) {
                $query->where('batch_id', Batch::currentbatch());
            })->badge('Total Skid'. ' ' . Skidweight::query()->where('batch_id', Batch::currentbatch())->count()),
        ];
        
    }
}
