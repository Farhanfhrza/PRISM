<?php

namespace App\Filament\Resources\InsertStockResource\Pages;

use App\Filament\Resources\InsertStockResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInsertStock extends EditRecord
{
    protected static string $resource = InsertStockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
