<?php

namespace App\Filament\Resources\OpnameDetailResource\Pages;

use App\Filament\Resources\OpnameDetailResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOpnameDetails extends ListRecords
{
    protected static string $resource = OpnameDetailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
