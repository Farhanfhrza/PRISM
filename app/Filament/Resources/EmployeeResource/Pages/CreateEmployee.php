<?php

namespace App\Filament\Resources\EmployeeResource\Pages;

use App\Filament\Resources\EmployeeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Facades\Filament;


class CreateEmployee extends CreateRecord
{
    protected static string $resource = EmployeeResource::class;

    // protected function mutateFormDataBeforeCreate(array $data): array
    // {
    //     $data['div_id'] = Filament::auth()->user()?->div_id;
    //     return $data;
    // }
}
