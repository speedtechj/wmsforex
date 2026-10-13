<?php

namespace App\Filament\Pages;

use App\Models\Agentinvoices;
use Filament\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Page;

class Searchagent extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationLabel = 'Search Agent Invoice';
    public static ?string $label = 'Scan Invoice';
     protected ?string $heading = 'Search Agent Invoice';
   protected static ?int $navigationSort = 1;
    protected static string $view = 'filament.pages.searchagent';
    public ?array $data = [];
    //public array $scannedInvoices = [];
    public $scannedInvoices = null;
 //   public $customerinfos = [];
    public $boxcount = null;
    public function mount(): void
    {
        //   $this->form->fill();
    }
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()

                    ->schema([
                        TextInput::make('scan_invoice')
                            ->label('Scan Invoice')
                            ->required()
                            ->numeric()
                            ->placeholder('Scan or type invoice number')
                            ->autofocus()
                            ->extraInputAttributes([
                                'autocomplete' => 'off',
                                'wire:keydown.enter' => 'submitScannedInvoice',
                            ])
                        //   ->suffixAction(
                        //     Action::make( 'submitScan' )
                        //     ->icon( 'heroicon-m-qr-code' )
                        //     ->label( 'Scan' )
                        //     ->action( 'submitScannedInvoice' )
                        // )->helperText( 'Scan or type the invoice number and press Enter or click Scan.' )

                    ])->columns('1')

            ])->statePath('data');
    }
    public function submitScannedInvoice(): void {
        $barcode = $this->data[ 'scan_invoice' ] ?? null;
        $agentinvoice =Agentinvoices::where('manual_invoice', $barcode)->firstOrFail();
      //  dd($agentinvoice->agent->full_name);
        if ( blank( $barcode ) ) {
            return;
        }

        // do whatever you need with the scanned/typed value here
    //   $this->handleScannedInvoice( $barcode );
         $this->scannedInvoices = $agentinvoice;
        // clear the field, ready for next scan/entry
        $this->data[ 'scan_invoice' ] = null;
    }
}
