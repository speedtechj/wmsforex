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
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class Boxquery extends Page implements HasForms, HasTable
{
    
   
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
                ->autocomplete(false)
                ->autofocus()
                ->minLength(6)
                ->maxLength(7)
               //->live()
                 ->prefixIcon('heroicon-o-qr-code')
                ->prefixIconColor('success')
                ->required()
                ->extraAttributes(['wire:keydown.enter.prevent' => 'search'])
               
            ])
           ->statePath('data');
    }

    public function search(): void
    {
        $this->validate();
    $this->searchid = Booking::where('booking_invoice', trim($this->data['box_query']))->orWhere('manual_invoice',trim($this->data['box_query']))->first()->sender_id ?? '';
        if($this->searchid == ''){
            Notification::make()
            ->title('No Record Found')
            ->warning()
            ->send();
            return;
        }
       // dd( $this->searchid);
    $this->data['box_query'] = " ";
   $this->form->fill();

       
}
    public function table(Table $table): Table
    {
        return $table
          ->query(Booking::query()->Within2weeks()->where('sender_id', trim($this->searchid)))
       // ->query(Booking::query()->where('sender_id', trim($this->searchid)))
   //   ->query(function (Builder $query ){
  //  return Booking::query()->where('sender_id', trim($this->searchid));
      
       // Booking::$query()->where('sender_id', trim($this->searchid));
         // dd($query->get());
   //   })
            ->columns([
                TextColumn::make('invoice')->label('Invoice')
                ->getStateUsing(function (Model $record){
                    if($record->manual_invoice != null){
                        return $record->manual_invoice;
                    }else{
                        return $record->booking_invoice;
                    }
                }),
                 TextColumn::make('batchno')
                ->label('Batch No')
               ->getStateUsing(function (Booking $record) {
                   return $record->batch ? $record->batch->batchno .'-'. $record->batch->batch_year : 'N/A';
               }),
                TextColumn::make('sender.full_name')->label('Sender'),
                TextColumn::make('boxtype.description')->label('Boxtype'),
               
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

//    protected function getTableQuery(): Builder
//     {
//         $query = Booking::query();
//         dd($query->get());
//         // return parent::getTableQuery()
//         //     ->where('status', 'published') // Filter for published posts
//         //     ->with('author'); // Eager load the author relationship
//     }
    
}
