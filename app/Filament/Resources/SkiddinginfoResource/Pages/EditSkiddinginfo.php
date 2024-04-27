<?php

namespace App\Filament\Resources\SkiddinginfoResource\Pages;

use App\Filament\Resources\SkiddinginfoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSkiddinginfo extends EditRecord
{
    protected static string $resource = SkiddinginfoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
