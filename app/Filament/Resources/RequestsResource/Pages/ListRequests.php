<?php

namespace App\Filament\Resources\RequestsResource\Pages;

use App\Filament\Resources\RequestsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\RequestsResource\Pages\RequestDetail;
use App\Models\Requests;
use Illuminate\Support\Facades\Auth;


class ListRequests extends ListRecords
{
    protected static string $resource = RequestsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            // Tombol Edit
            Actions\EditAction::make(),

            // Tombol View Detail
            Actions\Action::make('detail')
                ->label('View Detail')
                ->url(fn (Requests $record): string => RequestDetail::getUrl(['record' => $record])),
        ];
    }

    protected static ?string $title = null;

    public function getTitle(): string
    {
        $divisionName = Auth::user()?->division?->name ?? 'Divisi Tidak Diketahui';
        return "Requests Divisi {$divisionName}";
    }
}
