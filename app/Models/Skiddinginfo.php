<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Skiddinginfo extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function booking() : BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
    
    public function batch() : BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
}
