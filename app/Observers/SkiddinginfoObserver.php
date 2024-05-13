<?php

namespace App\Observers;

use App\Models\Boxtype;
use App\Models\Skiddinginfo;

class SkiddinginfoObserver
{
    /**
     * Handle the Skiddinginfo "created" event.
     */
    public function created(Skiddinginfo $skiddinginfo): void
    {
        
        // if($skiddinginfo->booking->boxtype_id == 4){
        //     $length = $skiddinginfo->booking->irregular_width;
        //     $width = $skiddinginfo->booking->irregular_height;
        //     $height = $skiddinginfo->booking->irregular_length;
        //     $boxcbm = round($length * $width * $height / 61024, 2);
        //     $skiddinginfo->update(['cbm' => $boxcbm]);
        // }else {
            $length = $skiddinginfo->booking->boxtype->lenght ?? 0;
            $width = $skiddinginfo->booking->boxtype->width ?? 0;
            $height = $skiddinginfo->booking->boxtype->height ?? 0;
            $boxcbm = round($length * $width * $height / 61024, 2);
            $skiddinginfo->update(['cbm' => $boxcbm]);
        // }
    }
    /**
     * Handle the Skiddinginfo "updated" event.
     */
    public function updated(Skiddinginfo $skiddinginfo): void
    {
        //
    }

    /**
     * Handle the Skiddinginfo "deleted" event.
     */
    public function deleted(Skiddinginfo $skiddinginfo): void
    {
        //
    }

    /**
     * Handle the Skiddinginfo "restored" event.
     */
    public function restored(Skiddinginfo $skiddinginfo): void
    {
        //
    }

    /**
     * Handle the Skiddinginfo "force deleted" event.
     */
    public function forceDeleted(Skiddinginfo $skiddinginfo): void
    {
        //
    }
}
