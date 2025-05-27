<?php

namespace App\Filament\Resources\InsertStockResource\Pages;

use App\Filament\Resources\InsertStockResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Facades\Filament;

class CreateInsertStock extends CreateRecord
{
    protected static string $resource = InsertStockResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['inserted_by'] = Filament::auth()->user()?->id;
        return $data;
    }
}
