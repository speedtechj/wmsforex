<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receiveraddress extends Model
{
    use HasFactory;
    protected $table = 'receiveraddresses';
    
    public function provincephil(){
        return $this->belongsTo(Provincephil::class);
    }
    public function cityphil(){
        return $this->belongsTo(Cityphil::class);
    }

    public function barangayphil(){
        return $this->belongsTo(Barangayphil::class);
    }
}
