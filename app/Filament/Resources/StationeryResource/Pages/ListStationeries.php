<?php

namespace App\Filament\Resources\StationeryResource\Pages;

use App\Filament\Resources\StationeryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

class ListStationeries extends ListRecords
{
    protected static string $resource = StationeryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected static ?string $title = null;

    public function getTitle(): string
    {
        $divisionName = Auth::user()?->division?->name ?? 'Divisi Tidak Diketahui';
        return "Stationery Divisi {$divisionName}";
    }
}
