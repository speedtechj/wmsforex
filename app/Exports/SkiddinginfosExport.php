<?php

namespace App\Exports;

use App\Models\Skiddinginfo;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;


class SkiddinginfosExport implements ShouldAutoSize, FromQuery, WithMapping, WithHeadings
{
    use Exportable;
    public $skiddinginfo;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct(Collection $skiddinginfo)
    {
       
        $this->skiddinginfo = $skiddinginfo;
    }
    public function query()
    {
        return  Skiddinginfo::wherekey($this->skiddinginfo->pluck('id')->toArray());
       
        
    }
    public function map($skiddinginfo): array
    {
       
        
        return [
            $skiddinginfo->batch->batchno,
            $skiddinginfo->skidno,
            $skiddinginfo->booking->booking_invoice ?? ' ',
            $skiddinginfo->booking->manual_invoice ?? ' ',
            $skiddinginfo->booking->boxtype->description ?? '',
            $skiddinginfo->cbm,
            
            // $booking->manual_invoice,
            


                    
        ];
    }
    public function headings(): array
    {
        return [
            'Batch No',
            'Skid No',
            'Generated Invoice',
            'Manual Invoice',
            'Box Type',
            'CBM',
              
        ];
    }
}
