<?php

namespace App\Exports;

use App\Models\Skidweight;
use App\Models\Skiddinginfo;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;


class SkidweightsExport implements  
FromQuery,
ShouldAutoSize, 
WithMapping, 
WithHeadings
{
    use Exportable;
    public $skidweight;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct(Collection $skidweight)
    {
       
        $this->skidweight = $skidweight;
    }
    public function query()
    {
        return  Skidweight::wherekey($this->skidweight->pluck('id')->toArray());
       
       
        
       
        
    }
    public function map($skidweight): array
    {
      
       
      
        return [
            $skidweight->batch->batchno,
            $skidweight->skid_no,
           $skidweight->total = Skiddinginfo::where('skidno', $skidweight->skid_no)
           ->where('batch_id', $skidweight->batch_id)->count(),
           $skidweight->weight,
        


                    
        ];
    }
    public function headings(): array
    {
        return [
            'Batch Number',
            'Skid Number',
            'Total Boxes',
            'Total Weight',
            
        ];
    }

}
