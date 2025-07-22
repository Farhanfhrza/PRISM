<?php

namespace App\Filament\Resources\InsertStockResource\Pages;

use Filament\Actions;
use App\Models\Stationery;
use App\Models\StockOpname;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\InsertStockResource;

class CreateInsertStock extends CreateRecord
{
    protected static string $resource = InsertStockResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $stationery = Stationery::find($data['stationery_id']);

        if ($stationery) {
            $opnameBerjalan = StockOpname::where('div_id', $stationery->div_id)
                ->whereNotIn('opname_status', ['Completed', 'Cancelled'])
                ->exists();

            if ($opnameBerjalan) {
                Notification::make()
                    ->title('Opname Berlangsung')
                    ->body('Tidak bisa menambah stok saat opname belum selesai.')
                    ->danger()
                    ->send();

                abort(403, 'Opname masih berjalan di divisi ini');
            }
        }

        $data['inserted_by'] = Filament::auth()->user()?->id;
        return $data;
    }
}
