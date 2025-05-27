<?php

namespace App\Observers;

use App\Models\Requests;
use App\Models\Stationery;
use App\Models\Transaction;
use App\Models\Request_detail;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\DB;

class RequestObserver
{
    /**
     * Handle the Requests "created" event.
     */
    public function created(Requests $requests): void {}

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
    public function deleted(Requests $requests): void {}

    /**
     * Handle the Requests "restored" event.
     */
    public function restored(Requests $requests): void
    {
        // $user = Filament::auth()->user();
        // $stationeries = Request_detail::where('request_id', $requests->id)->get()->toArray();

        // foreach ($stationeries as $stationery) {
        //     $stok = Stationery::find($stationery['stationery_id']);
        //     $stok->stock -= $stationery['amount']; // Kurangi stok
        //     $stok->save();

        //     Transaction::create([
        //         'user_id' => $user?->id,
        //         'stationery_id' => $stationery['stationery_id'],
        //         'transaction_type' => 'Out',
        //         'amount' => $stationery['amount'],
        //         'description' => "Pengguna {$user->name} mengembalikan data request, mengurangi stok {$stok->name} sebanyak {$stationery['amount']} {$stok->unit}",
        //         'source_type' => 'Insert Stationery Stock',
        //         'source_id' => $requests->id,
        //         'created_at' => now(),
        //     ]);
        // }
    }

    public function deleting(Requests $requests): void
    {
        // Simpan data sebelum dihapus ke properti
        $requests->details_before_force_delete = Request_detail::withTrashed()
            ->where('request_id', $requests->id)
            ->get();
    }

    /**
     * Handle the Requests "force deleted" event.
     */
    public function forceDeleted(Requests $requests): void
    {
        // Ambil semua request_detail (termasuk yang sudah di-softDelete)
        $user = Filament::auth()->user();
        $stationeries = $requests->details_before_force_delete ?? collect();

        foreach ($stationeries as $stationery) {
            $stok = Stationery::find($stationery->stationery_id);
            Transaction::create([
                'user_id' => $user->id,
                'stationery_id' => $stok->id,
                'transaction_type' => 'In',
                'amount' => $stationery->amount,
                'description' => "Pengguna {$user->name} menghapus data request, sehingga stok {$stok->name} bertambah sebanyak {$stationery->amount} {$stok->unit}",
                'source_type' => 'Request',
                'source_id' => $requests->id,
                'created_at' => now(),
            ]);
        }
    }
}
