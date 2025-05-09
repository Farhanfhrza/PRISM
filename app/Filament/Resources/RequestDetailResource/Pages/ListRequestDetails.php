<?php

namespace App\Filament\Resources\RequestDetailResource\Pages;

use App\Filament\Resources\RequestDetailResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRequestDetails extends ListRecords
{
    protected static string $resource = RequestDetailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
