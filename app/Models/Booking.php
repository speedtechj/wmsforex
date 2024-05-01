<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    protected $table = 'bookings';
    protected $guarded = [];
    
    public function boxtype()
    {
        return $this->belongsTo(Boxtype::class);
    }

    public function scopeSkidresult($query, $booking_invoice)
    {
        $query->where('booking_invoice', $booking_invoice)
            ->orWhere('manual_invoice', $booking_invoice);
            
    }

   
}
