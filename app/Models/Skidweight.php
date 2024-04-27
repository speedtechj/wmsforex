<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Skidweight extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function batch() :BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }
    public function user() :BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
