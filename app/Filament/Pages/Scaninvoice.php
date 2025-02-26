<?php

namespace App\Filament\Pages;

use App\Models\User;
use App\Models\Batch;
use Livewire\Livewire;
use App\Models\Booking;
use App\Models\Skidcnt;
use Filament\Forms\Set;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Pages\Page;
use App\Models\Skidweight;
use Filament\Tables\Table;
use App\Models\Skiddinginfo;
use App\Models\Skidgallery;
use Filament\Support\Enums\MaxWidth;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Enums\FontFamily;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\Layout\Grid;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\Layout\Panel;
use Filament\Tables\Columns\Layout\Split;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Columns\TextColumn\TextColumnSize;

class Scaninvoice extends Page implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;
    protected static ?string $navigationIcon = 'heroicon-o-qr-code';
    protected static ?string $navigationLabel = 'Scan Barcode';
    public static ?string $label = 'Scan Barcode';
    protected static string $view = 'filament.pages.scaninvoice';
    protected static string $layout = 'filament-panels::components.layout.index';
    public ?array $data = [];
    public $skidno;
    public $booking_invoice;


    public function mount(): void
    {
        $this->skidno = Skidcnt::get()->first()->skid_count;
    }
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->description('Click on the weight icon to create skid weight.')
                    ->aside()
                    ->schema([
                        TextInput::make('booking_invoice')
                            ->required()
                            ->length(7)
                            ->numeric()
                            // ->live()
                            ->autocomplete(false)
                            ->label('Scan Invoice')
                            ->placeholder('Scan Invoice')
                            ->autofocus()
                            ->prefixIcon('heroicon-o-qr-code')
                            ->prefixIconColor('success')
                            ->suffixAction(
                                Action::make('weight')
                                ->visible(function (array $data){
                                    $skidinforesult = Skiddinginfo::where('skidno', $this->skidno)
                                    ->where('batch_id', Batch::currentbatch())->count();
                                    if($skidinforesult == 0){
                                        return false;
                                    }else {
                                        return true;
                                    }
                                    
                                })
                                ->modalWidth(MaxWidth::Large)
                                ->requiresConfirmation()
                                ->modalHeading(function (array $data) {
                                  $total_box = Skiddinginfo::where('skidno', $this->skidno)
                                    ->where('batch_id', Batch::currentbatch())->count();
                                   return 'Total Box: '.' '.$total_box;
                                })
                                ->modalDescription('Please check the total box count before entering the weight.')
                                ->modalSubmitActionLabel('Yes, Save')
                                    ->slideOver()
                                    ->form([
                                        Section::make('Enter Weight')
                                            ->schema(static::getWeighform())->columns(1),
                                    ])
                                    ->icon('heroicon-o-scale')
                                    ->color('danger')

                                    ->action(function (Component $livewire, Set $set, $state, array $data) {
                                        $skidwtresult = Skidweight::where('skid_no', $this->skidno)
                                        ->where('batch_id', Batch::currentbatch())->count();
                                       
                                       
                                        if($skidwtresult == 0){
                                            Skidcnt::get()->first()->update([
                                                'skid_count' => $this->skidno + 1
                                            ]);
                                           
                                            Skidweight::create([
                                                'skid_no' => $this->skidno,
                                                'weight' => $data['weight'],
                                                'batch_id' => Batch::Currentbatch(),
                                                'user_id' => \Illuminate\Support\Facades\Auth::user()->id,
                                            ]);
                                            Skidgallery::create([
                                                'skidid' => $this->skidno,
                                                'gallery' => $data['attachment'],
                                                'batch_id' => Batch::Currentbatch(),
                                                'user_id' => \Illuminate\Support\Facades\Auth::user()->id,
                                            ]);
                                            Notification::make()
                                                ->title('Weight Saved successfully & New skidno created') 
                                                ->success()
                                                ->send();
                                            $this->resetTable();
                                            $this->skidno = Skidcnt::get()->first()->skid_count;
                                        } else {
                                            Notification::make()
                                                ->title('Weight Already Exist or No Invoice Found')
                                                ->success()
                                                ->send();
                                        }
                                        
                                    })
                            )

                    ])->columns('1')

            ])->statePath('data');
    }
    public function create(): void
    {
       
        $this->skidno = Skidcnt::get()->first()->skid_count;
        $skidresult = Booking::Skidresult($this->data['booking_invoice'])->first();
        $searchskid = Skiddinginfo::Searchskid($this->data['booking_invoice'])->get()->first();
        $bookdata = Booking::Skidresult($this->data['booking_invoice'])->exists();
      
            if($bookdata){
                $skidresult->update([
                    'batch_id' => Batch::Currentbatch(),
                ]);
                $encode = true;
               
            };
        if (!$searchskid) {
            Skiddinginfo::create([
                'skidno' => $this->skidno,
                'booking_id' => $skidresult->id ?? null,
                'virtual_invoice' => $this->data['booking_invoice'],
                'batch_id' => Batch::Currentbatch(),
                'user_id' => \Illuminate\Support\Facades\Auth::user()->id,
                'is_encode' => $encode ?? false,
            ]);
           
            Notification::make()
                ->title('Invoice Scan successfully')
                ->success()
                ->send();
        } else {
            Notification::make()
                ->title('Invoice Already Scan')
                ->danger()
                ->send();
        }
        $this->form->fill();

    }
    public function table(Table $table): Table
    {
        return $table
       
            ->paginated(false)
            ->query(Skiddinginfo::query()->where('skidno', $this->skidno)->where('batch_id', Batch::currentbatch()))
            ->columns([
                TextColumn::make('skidno')
                    ->label('Skid No')
                    ->size(TextColumnSize::Large)
                    ->weight(FontWeight::Bold)
                    ->fontFamily(FontFamily::Mono),
                TextColumn::make('virtual_invoice')
                    ->label('Invoice')
                    ->size(TextColumnSize::Large)
                    ->weight(FontWeight::Bold)
                    ->fontFamily(FontFamily::Mono),
                TextColumn::make('booking.boxtype.description')
                    ->label('Box Type')
                    ->size(TextColumnSize::Large)
                    ->weight(FontWeight::Bold)
                    ->fontFamily(FontFamily::Mono),
                TextColumn::make('batch.batchno')
                    ->size(TextColumnSize::Large)
                    ->weight(FontWeight::Bold)
                    ->fontFamily(FontFamily::Mono)
                    ->label('Batch Number'),


            ])->defaultSort('created_at', 'desc')

            ->filters([
                // ...
            ])
            ->actions([
                DeleteAction::make()
                    ->icon('heroicon-o-x-mark')
                    ->iconButton()
                    ->after(function(Model $record){
                        $updatebatchid = Booking::where('id', $record->booking_id)->get()->first();
                        if($updatebatchid){
                            $updatebatchid->update([
                                'batch_id' => 23
                            ]);
                        }
                       
                    }),
            ])
            ->bulkActions([
                // ...
            ]);
    }
    public function getMaxContentWidth(): MaxWidth
    {
        return MaxWidth::FitContent;
    }

    public static function getWeighform(): array
    {
        return [
            FileUpload::make('attachment')
                ->label('Attachment')
                ->multiple()
                ->panelLayout('grid')
                ->uploadingMessage('Uploading attachment...')
                ->image()
                ->disk('public')
                ->directory('skidgallery')
                ->visibility('private')
                ->required()
                ->minFiles(4),
            TextInput::make('weight')
                ->numeric()
                ->label('Weight')
                ->placeholder('Weight')
                ->autofocus()
                ->prefixIcon('heroicon-o-scale')
                ->prefixIconColor('success')
                ->suffix('Lbs')
                ->autocomplete(false)
                ->minValue(1)
                ->maxValue(2500)
                ->required(),
                
               

        ];
    }

}
