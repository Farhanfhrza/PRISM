<?php

namespace App\Observers;

use App\Models\Stationery;
use App\Models\InsertStock;
use App\Models\StockOpname;
use App\Models\Transaction;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;

class InsertStockObserver
{
    /**
     * Handle the InsertStock "created" event.
     */
    public function created(InsertStock $insertStock): void
    {
        Stationery::where('id', $insertStock->stationery_id)
            ->increment('stock', $insertStock->amount);

        $user = $insertStock->user;
        $stationery = Stationery::find($insertStock->stationery_id);

        Transaction::create([
            'user_id' => $user?->id,
            'stationery_id' => $insertStock->stationery_id,
            'transaction_type' => 'In',
            'div_id' => $user?->div_id,
            'amount' => $insertStock->amount,
            'description' => "Pengguna {$user->name} menambah stok {$stationery->name} sebanyak {$insertStock->amount} {$stationery->unit}",
            'source_type' => 'Insert Stationery Stock',
            'source_id' => $insertStock->id,
            'created_at' => now(),
        ]);
    }

    /**
     * Handle the InsertStock "updated" event.
     */
    public function updated(InsertStock $insertStock): void
    {
        //
    }

    public function deleting(InsertStock $insertStock): void
    {
        $stationery = $insertStock->stationery; // pastikan ada relasi stationery()

        if ($stationery) {
            $divId = $stationery->div_id;

            $opnameBerjalan = StockOpname::where('div_id', $divId)
                ->whereNotIn('opname_status', ['Completed', 'Cancelled'])
                ->exists();

            if ($opnameBerjalan) {
                // Cegah penghapusan
                Notification::make()
                    ->title('Stock Opname Berlangsung')
                    ->body('Tidak bisa menghapus permintaan ATK karena sedang berlangsung stock opname di divisi ini.')
                    ->danger()
                    ->send();

                abort(403, 'Stock opname masih berjalan di divisi ini');
            }
        }
    }

    /**
     * Handle the InsertStock "deleted" event.
     */
    public function deleted(InsertStock $insertStock): void
    {
        Stationery::where('id', $insertStock->stationery_id)
            ->decrement('stock', $insertStock->amount);

        $user = $insertStock->user;
        $stationery = Stationery::find($insertStock->stationery_id);

        Transaction::create([
            'user_id' => $user?->id,
            'stationery_id' => $insertStock->stationery_id,
            'transaction_type' => 'Out',
            'div_id' => $user?->div_id,
            'amount' => $insertStock->amount,
            'description' => "Pengguna {$user->name} menghapus data tambah stok {$stationery->name} sebanyak {$insertStock->amount} {$stationery->unit}",
            'source_type' => 'Insert Stationery Stock',
            'source_id' => $insertStock->id,
            'created_at' => now(),
        ]);
    }

    /**
     * Handle the InsertStock "restored" event.
     */
    public function restored(InsertStock $insertStock): void
    {
        //
    }

    /**
     * Handle the InsertStock "force deleted" event.
     */
    public function forceDeleted(InsertStock $insertStock): void
    {
        //
    }
}
