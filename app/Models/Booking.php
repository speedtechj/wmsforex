<?php

namespace App\Models;

use App\Models\Scopes\InvloadedScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;

//#[ScopedBy([InvloadedScope::class])]
class Booking extends Model
{
    use SoftDeletes;
    use HasFactory;
    protected $table = 'bookings';
    protected $guarded = [];
    
    public function boxtype()
    {
        return $this->belongsTo(Boxtype::class);
    }
    public function sender()
    {
        return $this->belongsTo(Sender::class);
    }
    public function senderaddress()
    {
        return $this->belongsTo(Senderaddress::class);
    }
    public function receiver()
    {
        return $this->belongsTo(Receiver::class);
    }

    public function batch ()
    {
        return $this->belongsTo(Batch::class);
    }
    public function receiveraddress()
    {
        return $this->belongsTo(Receiveraddress::class);
    }
    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function skiddinginfo(): BelongsTo
    {
        return $this->belongsTo(Skiddinginfo::class);
    }
   
    public function scopeSkidresult($query, $booking_invoice)
    {
      return $query->where('booking_invoice', $booking_invoice)
            ->orWhere('manual_invoice', $booking_invoice);
          
        
    }

   
}
