<?php

namespace App\Filament\Resources\StationeryResource\Pages;

use Filament\Actions;
use App\Models\Stationery;
use App\Models\StockOpname;
use Filament\Facades\Filament;
use Filament\Pages\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\StationeryResource;


class CreateStationery extends CreateRecord
{
    protected static string $resource = StationeryResource::class;

    // protected function mutateFormDataBeforeCreate(array $data): array
    // {
    //     $data['div_id'] = Filament::auth()->user()?->div_id;
    //     return $data;
    // }

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
        return $data;
    }

    // protected function getHeaderActions(): array
    // {
    //     return [
    //         Action::make('scan-barcode')
    //             ->label('Scan Barcode')
    //             ->modalHeading('Pindai Barcode')
    //             ->modalContent(function () {
    //                 return view('filament.stationery.barcode-scanner');
    //             }),
    //     ];
    // }
}
