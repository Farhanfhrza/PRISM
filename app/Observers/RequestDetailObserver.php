<?php

namespace App\Observers;

use App\Models\Requests;
use App\Models\Request_detail;
use App\Models\Stationery;

class RequestDetailObserver
{
    /**
     * Handle the Request_detail "created" event.
     */
    public function created(Request_detail $request_detail): void
    {
        //
    }

    /**
     * Handle the Request_detail "updated" event.
     */
    public function updated(Request_detail $request_detail): void
    {
        $oldAmount = $request_detail->getOriginal('amount');
        $newAmount = $request_detail->amount;

        $stationery = Stationery::find($request_detail->stationery_id);

        // Kembalikan stok lama, lalu kurangi stok baru
        $stationery->stock += $oldAmount;
        $stationery->stock -= $newAmount;
        $stationery->save();
    }

    /**
     * Handle the Request_detail "deleted" event.
     */
    public function deleted(Request_detail $request_detail): void
    {
        $stationery = Stationery::find($request_detail->stationery_id);
        $stationery->stock += $request_detail->amount; // Kembalikan stok
        $stationery->save();
    }

    /**
     * Handle the Request_detail "restored" event.
     */
    public function restored(Request_detail $request_detail): void
    {
        //
    }

    /**
     * Handle the Request_detail "force deleted" event.
     */
    public function forceDeleted(Request_detail $request_detail): void
    {
        //
    }
}
