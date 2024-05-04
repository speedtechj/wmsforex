<?php

namespace App\Filament\Resources\SkidsummaryResource\Pages;

use App\Filament\Resources\SkidsummaryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSkidsummary extends EditRecord
{
    protected static string $resource = SkidsummaryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
