<?php

namespace App\Filament\Resources\SkidgalleryResource\Pages;

use App\Filament\Resources\SkidgalleryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSkidgallery extends EditRecord
{
    protected static string $resource = SkidgalleryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }
}
