<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    protected $table = 'bookings';
    protected $guarded = [];
    // public function skiddinginfo() : HasMany
    // {
    //     return $this->hasMany(Skiddinginfo::class);
    // }
    
    
    public function boxtype()
    {
        return $this->belongsTo(Boxtype::class);
    }

    
    
    

    
    // public function batch()
    // {
    //     return $this->belongsTo(Batch::class);
    // }
   
}
