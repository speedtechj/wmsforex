<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Manifestskid extends Model
{
    use HasFactory;
    protected $table = 'bookings';
    protected $guarded = [];
    
    public function boxtype() : BelongsTo
    {
        return $this->belongsTo(Boxtype::class);
    }
    public function skiddinginfo() : BelongsTo
    {
        return $this->belongsTo(Skiddinginfo::class);
    }
    public function batch() : BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }
}
