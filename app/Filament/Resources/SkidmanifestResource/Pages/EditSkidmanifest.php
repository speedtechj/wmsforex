<?php

namespace App\Filament\Resources\SkidmanifestResource\Pages;

use App\Filament\Resources\SkidmanifestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSkidmanifest extends EditRecord
{
    protected static string $resource = SkidmanifestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
