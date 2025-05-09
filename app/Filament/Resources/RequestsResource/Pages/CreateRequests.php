<?php

namespace App\Filament\Resources\RequestsResource\Pages;

use App\Filament\Resources\RequestsResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Request_detail;
use App\Models\Stationery;


class CreateRequests extends CreateRecord
{
    protected static string $resource = RequestsResource::class;

    protected function afterCreate(): void {
        $stationeries = Request_detail::where('request_id', $this->record->id)->get()->toArray();
        foreach ($stationeries as $stationery){
            $stok = Stationery::find($stationery['stationery_id']);
            $stok->stock -= $stationery['amount'];
            $stok->save();
        }
    }

}
