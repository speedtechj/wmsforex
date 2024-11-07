<?php

namespace App\Filament\Resources\SettingdateResource\Pages;

use Filament\Actions;
use App\Models\Settingdate;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\SettingdateResource;

class EditSettingdate extends EditRecord
{
    protected static string $resource = SettingdateResource::class;

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
