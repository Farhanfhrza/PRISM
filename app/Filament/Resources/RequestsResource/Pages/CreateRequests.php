<?php

namespace App\Filament\Resources\RequestsResource\Pages;

use Filament\Actions;
use App\Models\Stationery;
use App\Models\Transaction;
use App\Models\Request_detail;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\RequestsResource;


class CreateRequests extends CreateRecord
{
    protected static string $resource = RequestsResource::class;

    protected function afterCreate(): void
    {
        $user = Filament::auth()->user();
        $stationeries = Request_detail::where('request_id', $this->record->id)->get();
        foreach ($stationeries as $stationery) {
            $stok = Stationery::find($stationery->stationery_id);
            $stok->stock -= $stationery->amount;
            $stok->save();

            Transaction::create([
                'user_id' => $user?->id,
                'stationery_id' => $stok->id,
                'transaction_type' => 'Out',
                'amount' => $stationery->amount,
                'description' => "Pengguna {$user->name} me-request {$stok->name} sebanyak {$stationery->amount} {$stok->unit}",
                'source_type' => 'Request',
                'source_id' => $this->record->id,
                'created_at' => now(),
            ]);
        }
    }
}
