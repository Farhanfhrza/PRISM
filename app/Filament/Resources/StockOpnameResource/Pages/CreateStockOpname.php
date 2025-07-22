<?php

namespace App\Filament\Resources\StockOpnameResource\Pages;

use Filament\Actions;
use App\Models\StockOpname;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\StockOpnameResource;

class CreateStockOpname extends CreateRecord
{
    protected static string $resource = StockOpnameResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = auth()->user();
        $divId = $user->div_id;

        $hasRunningOpname = StockOpname::where('div_id', $divId)
            ->whereNotIn('opname_status', ['Completed', 'Cancelled'])
            ->exists();

        if ($hasRunningOpname) {
            Notification::make()
                ->title('Opname Belum Selesai')
                ->body('Masih ada stock opname yang belum selesai di divisi Anda.')
                ->danger()
                ->send();

            abort(403, 'Masih ada opname yang belum selesai.');
        }

        $data['initiated_by'] = Filament::auth()->user()?->id;
        $data['div_id'] = Filament::auth()->user()?->div_id;
        return $data;
    }
}
