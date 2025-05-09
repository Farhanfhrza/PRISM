<?php

namespace App\Observers;

use App\Models\Requests;
use App\Models\Request_detail;
use App\Models\Stationery;
use Illuminate\Support\Facades\DB;

class RequestObserver
{
    /**
     * Handle the Requests "created" event.
     */
    public function created(Requests $requests): void
    {
        //
    }

    /**
     * Handle the Requests "updated" event.
     */
    public function updated(Requests $requests): void
    {
        // Ambil request_detail lama (sebelum di-update)
        DB::transaction(function () use ($requests) {
            // Kembalikan stok lama
            $oldDetails = Request_detail::where('request_id', $requests->id)->get();
            foreach ($oldDetails as $detail) {
                $stationery = Stationery::find($detail->stationery_id);
                $stationery->stock += $detail->amount;
                $stationery->save();
            }

            // Kurangi stok baru
            $newDetails = Request_detail::where('request_id', $requests->id)->get();
            foreach ($newDetails as $detail) {
                $stationery = Stationery::find($detail->stationery_id);
                $stationery->stock -= $detail->amount;
                $stationery->save();
            }
        });
    }

    /**
     * Handle the Requests "deleted" event.
     */
    public function deleted(Requests $requests): void
    {
        // $stationeries = Request_detail::where('request_id', $requests->id)->get()->toArray();

        // foreach ($stationeries as $stationery) {
        //     $stok = Stationery::find($stationery['stationery_id']);
        //     $stok->stock += $stationery['amount'];
        //     $stok->save();
        // }
    }

    /**
     * Handle the Requests "restored" event.
     */
    public function restored(Requests $requests): void
    {
        $stationeries = Request_detail::where('request_id', $requests->id)->get()->toArray();

        foreach ($stationeries as $stationery) {
            $stok = Stationery::find($stationery['stationery_id']);
            $stok->stock -= $stationery['amount']; // Kurangi stok
            $stok->save();
        }
    }

    /**
     * Handle the Requests "force deleted" event.
     */
    public function forceDeleted(Requests $requests): void
    {
        // Ambil semua request_detail (termasuk yang sudah di-softDelete)
        $stationeries = Request_detail::withTrashed()->where('request_id', $requests->id)->get();

        foreach ($stationeries as $stationery) {
            $stok = Stationery::find($stationery->stationery_id);
            if ($stok) {
                $stok->stock += $stationery->amount;
                $stok->save();
            }
        }
    }
}
