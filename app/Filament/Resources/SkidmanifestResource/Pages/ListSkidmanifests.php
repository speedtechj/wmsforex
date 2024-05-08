<?php

namespace App\Filament\Resources\SkidmanifestResource\Pages;

use App\Filament\Resources\SkidmanifestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSkidmanifests extends ListRecords
{
    protected static string $resource = SkidmanifestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
