<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Skidgallery extends Model
{
    protected $guarded = [];
    protected $casts = [
        'gallery' => 'array',
    ];
    public function batch() : BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
