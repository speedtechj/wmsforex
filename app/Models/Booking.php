<?php

namespace App\Models;

use App\Models\Scopes\InvloadedScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
#[ScopedBy([InvloadedScope::class])]
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
