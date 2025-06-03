<?php

namespace App\Filament\Resources\StationeryResource\Pages;

use App\Filament\Resources\StationeryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Facades\Filament;


class CreateStationery extends CreateRecord
{
    protected static string $resource = StationeryResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['div_id'] = Filament::auth()->user()?->div_id;
        return $data;
    }
}
