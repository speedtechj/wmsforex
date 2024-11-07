<?php

namespace App\Models\Scopes;

use App\Models\Settingdate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;

class InvloadedScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        $days = Settingdate::where('is_default', 1)->first()->setting_day ?? 30;
        $builder->whereDate('booking_date', '>=' , now()->subDays($days));
    }
}
