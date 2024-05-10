<?php

namespace App\Filament\Resources\ManifestskidResource\Pages;

use App\Filament\Resources\ManifestskidResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditManifestskid extends EditRecord
{
    protected static string $resource = ManifestskidResource::class;

    protected function getHeaderActions(): array
    {
        return [
           
        ];
    }
}
