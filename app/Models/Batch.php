<?php

namespace App\Models;

use App\Models\Skiddinginfo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Batch extends Model
{
    use HasFactory;
    protected $table = 'batches';
    protected $guarded = [];
    public function User(){
        return $this->belongsTo(User::class);
    }
    // public function skiddinginfo(): BelongsTo{
    //     return $this->belongsTo(Skiddinginfo::class);
    // }
     
    public function scopeCurrentbatch($query){
        return $query->where('is_current', true)->first()->id;
    }


}
