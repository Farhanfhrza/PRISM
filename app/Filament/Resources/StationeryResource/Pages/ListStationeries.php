<?php

namespace App\Filament\Resources\StationeryResource\Pages;

use App\Filament\Resources\StationeryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStationeries extends ListRecords
{
    protected static string $resource = StationeryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
