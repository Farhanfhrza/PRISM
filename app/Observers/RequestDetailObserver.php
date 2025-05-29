<?php

namespace App\Observers;

use App\Models\Requests;
use App\Models\Stationery;
use App\Models\Transaction;
use App\Models\Request_detail;
use Filament\Facades\Filament;

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
        $user = Filament::auth()->user();

        $oldAmount = $request_detail->getOriginal('amount');
        $newAmount = $request_detail->amount;
        $diff = ($oldAmount>$newAmount ? "In" : "Out");
        $diffAmount = abs($oldAmount-$newAmount);

        $stationery = Stationery::find($request_detail->stationery_id);

        Transaction::create([
            'user_id' => $user->id,
            'stationery_id' => $stationery->id,
            'transaction_type' => $diff,
            'div_id' => $user?->div_id,
            'amount' => $diffAmount,
            'description' => "Pengguna {$user->name} mengubah jumlah request stationery {$stationery->name} dari {$oldAmount} menjadi {$newAmount}",
            'source_type' => 'Request',
            'source_id' => $request_detail->request_id,
            'created_at' => now(),
        ]);

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
