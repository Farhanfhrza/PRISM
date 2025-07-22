<?php

namespace App\Filament\Widgets;

use App\Models\DemandForecast;
use Filament\Tables;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

class DemandForecastList extends BaseWidget
{

    protected static ?int $sort = 3;
    protected function getTableQuery(): Builder|Relation|null
    {
        $user = Auth::user();
        $divName = $user->division->name ?? '';
        $bulanDepan = now()->addMonth();

        return DemandForecast::query()
            ->where('division', $divName)
            ->whereMonth('date', $bulanDepan->month)
            ->whereYear('date', $bulanDepan->year)
            ->where('predicted_demand', '>', 0);
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('date')
                ->label('Bulan')
                ->date('F Y'),

            Tables\Columns\TextColumn::make('stationery_name')
                ->label('Nama Alat Tulis'),

            Tables\Columns\TextColumn::make('predicted_demand')
                ->label('Perkiraan Jumlah')
                ->formatStateUsing(fn($state) => number_format($state, 0)),

            // Tables\Columns\TextColumn::make('lower_bound')
            //     ->label('Batas Bawah')
            //     ->formatStateUsing(fn($state) => number_format($state, 0)),

            // Tables\Columns\TextColumn::make('upper_bound')
            //     ->label('Batas Atas')
            //     ->formatStateUsing(fn($state) => number_format($state, 0)),
        ];
    }

    protected function getTableHeading(): string
    {
        return 'Prediksi Permintaan Bulan Depan';
    }
}
