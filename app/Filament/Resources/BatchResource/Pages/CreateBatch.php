<?php

namespace App\Filament\Resources\BatchResource\Pages;

use App\Models\Batch;
use Filament\Actions;
use App\Models\Skidcnt;
use Filament\Notifications\Notification;
use App\Filament\Resources\BatchResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBatch extends CreateRecord
{
    protected static string $resource = BatchResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $batchno = Batch::where( 'batchno', '=', $data[ 'batchno' ] )
        ->where( 'batch_year', '=', $data[ 'batch_year' ] );
        $batchcurrent = Batch::where('is_current', true)->count();
        if ( $batchno->exists() || $batchcurrent > 0) {
            Notification::make()
            ->title( 'Record Already Exists or There is an open batch' )
            ->icon( 'heroicon-o-exclamation-circle' )
            ->iconColor( 'success' )
            ->send();
            $this->halt();
        } else {
        $data['user_id'] = auth()->id();
        Skidcnt::first()->update(['skid_count' => 1]);
       
       
        }
        return $data;
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
