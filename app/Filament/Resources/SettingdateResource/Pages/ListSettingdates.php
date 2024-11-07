<?php

namespace App\Filament\Resources\SettingdateResource\Pages;

use App\Filament\Resources\SettingdateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSettingdates extends ListRecords
{
    protected static string $resource = SettingdateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
