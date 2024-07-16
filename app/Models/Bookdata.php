<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bookdata extends Model
{
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
    public function receiveraddress()
    {
        return $this->belongsTo(Receiveraddress::class);
    }
    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }
}
