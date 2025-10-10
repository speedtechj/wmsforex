<?php

namespace App\Filament\Pages;

use App\Models\Batch;
use App\Models\Booking;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Tables\Table;
use App\Models\Skiddinginfo;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class Boxquery extends Page implements HasForms, HasTable
{
    
    use InteractsWithForms;
    protected static ?string $navigationIcon = 'heroicon-o-qr-code';
    protected static ?string $navigationLabel = 'Scan Boxquery';
    public static ?string $label = 'Scan Box Info';
   protected static ?int $navigationSort = 1;    protected static string $view = 'filament.pages.boxquery';
    protected static string $layout = 'filament-panels::components.layout.index';
     use InteractsWithForms;
    use InteractsWithTable;
    public ?array $data = [];
    public $box_query;
    public $searchid = '';
    public function mount(): void
    {
       $this->form->fill();
       
    }
    
     public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('box_query')
                ->autofocus()
                    ->required(),
            ])
           ->statePath('data');
    }

    public function search(): void
    {
      // dd($this->data['box_query']);
     $this->box_query = $this->data['box_query'];
    $this->searchid = Booking::where('booking_invoice', $this->box_query)->orWhere('manual_invoice',$this->box_query)->first()->sender_id ?? " ";
        // dd($this->searchid->sender_id);
    }
    public function table(Table $table): Table
    {
        return $table
           ->query(Booking::query()->where('sender_id', $this->searchid))
            ->columns([
                TextColumn::make('invoice')->label('Invoice')
                ->getStateUsing(function (Model $record){
                    if($record->manual_invoice != null){
                        return $record->manual_invoice;
                    }else{
                        return $record->booking_invoice;
                    }
                }),
                TextColumn::make('sender.full_name')->label('Sender'),
                TextColumn::make('boxtype.description')->label('Boxtype'),
                TextColumn::make('batchno')
                ->label('Batch No')
               ->getStateUsing(function (Booking $record) {
                   return $record->batch ? $record->batch->batchno .'-'. $record->batch->batch_year : 'N/A';
               }),
               TextColumn::make('skidno')->label('Skid No.')
               ->getStateUsing(function (Booking $record) {
                $skidno = Skiddinginfo::where('booking_id', $record->id)->first();
                return $skidno ? $skidno->skidno : 'N/A';
               }),
            ])->defaultSort('created_at','desc')
            ->filters([
                // ...
            ])
            ->actions([
                // ...
            ])
            ->bulkActions([
                // ...
            ]);
    }
    
}
