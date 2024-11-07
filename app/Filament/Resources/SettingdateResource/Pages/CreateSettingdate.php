<?php

namespace App\Filament\Resources\SettingdateResource\Pages;

use App\Models\User;
use Filament\Actions;
use App\Models\Settingdate;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\SettingdateResource;

class CreateSettingdate extends CreateRecord
{
    protected static string $resource = SettingdateResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
       
        $data['user_id'] = \Illuminate\Support\Facades\Auth::user()->id;
       
        $default_checker = Settingdate::query()->where('is_default', 1)->count();
        if($default_checker == 1){
            Notification::make()
            ->title('Unset default days')
            ->success()
            ->send();
            $this->halt();
        }
        return $data;
        
       

    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    
}
