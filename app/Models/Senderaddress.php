<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Senderaddress extends Model
{
    use HasFactory;
    protected $table = 'senderaddresses';
    public function citycan(){
        return $this->belongsTo(Citycan::class);
    }
    public function provincecan(){
        return $this->belongsTo(Provincecan::class);
    }
}
