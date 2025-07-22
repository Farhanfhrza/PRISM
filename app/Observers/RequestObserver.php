<?php

namespace App\Observers;

use App\Models\Requests;
use App\Models\Stationery;
use App\Models\StockOpname;
use App\Models\Transaction;
use App\Models\Request_detail;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;

class RequestObserver
{
    /**
     * Handle the Requests "created" event.
     */
    public function created(Requests $requests): void {}

    /**
     * Handle the Requests "updated" event.
     */
    public function updated(Requests $requests): void {}

    /**
     * Handle the Requests "deleted" event.
     */
    public function deleted(Requests $requests): void {}

    /**
     * Handle the Requests "restored" event.
     */
    public function restored(Requests $requests): void {}

    public function deleting(Requests $requests): void
    {
        $employee = $requests->employee; // pastikan ada relasi employee()

        if ($employee) {
            $divId = $employee->div_id;

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
                'div_id' => $user?->div_id,
                'amount' => $stationery->amount,
                'description' => "Pengguna {$user->name} menghapus data request, sehingga stok {$stok->name} bertambah sebanyak {$stationery->amount} {$stok->unit}",
                'source_type' => 'Request',
                'source_id' => $requests->id,
                'created_at' => now(),
            ]);
        }
    }
}
