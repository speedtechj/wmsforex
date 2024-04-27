<?php

namespace App\Filament\Resources\SkidweightResource\Pages;

use App\Filament\Resources\SkidweightResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSkidweight extends EditRecord
{
    protected static string $resource = SkidweightResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
