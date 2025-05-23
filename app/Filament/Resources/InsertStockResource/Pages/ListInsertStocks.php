<?php

namespace App\Filament\Resources\InsertStockResource\Pages;

use App\Filament\Resources\InsertStockResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInsertStocks extends ListRecords
{
    protected static string $resource = InsertStockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
