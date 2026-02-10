<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Agentinvoices extends Model
{
    protected $table = 'agentinvoices';

    public function agent() : BelongsTo
    {
        return $this->belongsTo(Agent::class);
    }
}
