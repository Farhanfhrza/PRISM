<?php

namespace App\Filament\Resources\RequestDetailResource\Pages;

use App\Filament\Resources\RequestDetailResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateRequestDetail extends CreateRecord
{
    protected static string $resource = RequestDetailResource::class;

    protected function afterCreate(): void {
        dd($this->record);
    }

}
