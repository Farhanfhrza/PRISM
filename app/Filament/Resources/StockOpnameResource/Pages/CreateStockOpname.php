<?php

namespace App\Filament\Resources\StockOpnameResource\Pages;

use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\StockOpnameResource;

class CreateStockOpname extends CreateRecord
{
    protected static string $resource = StockOpnameResource::class;
    
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['initiated_by'] = Filament::auth()->user()?->id;
        $data['div_id'] = Filament::auth()->user()?->div_id;
        return $data;
    }
}
