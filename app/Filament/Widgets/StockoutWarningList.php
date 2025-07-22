<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use App\Models\Division;
use App\Models\Stationery;
use App\Models\DemandForecast;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Relations\Relation;

class StockoutWarningList extends BaseWidget
{
    protected static ?int $sort = 4;

    protected function getTableQuery(): Builder|Relation|null
    {
        $user = Auth::user();
        $divId = $user->div_id;
        $divisionName = Division::find($divId)?->name;

        if (!$divisionName) {
            return Stationery::query()->whereRaw('0 = 1'); // return kosong
        }

        $bulanDepan = Carbon::now()->startOfMonth()->addMonth();

        return Stationery::query()
            ->where('stationeries.div_id', $divId)
            ->join('demand_forecasts', function ($join) use ($bulanDepan, $divisionName) {
                $join->on('stationeries.name', '=', 'demand_forecasts.stationery_name')
                    ->where('demand_forecasts.division', $divisionName)
                    ->whereMonth('demand_forecasts.date', $bulanDepan->month)
                    ->whereYear('demand_forecasts.date', $bulanDepan->year)
                    ->whereColumn('demand_forecasts.predicted_demand', '>', 'stationeries.stock');
            })
            ->select(
                'stationeries.*',
                'demand_forecasts.predicted_demand',
                'demand_forecasts.date as forecast_date'
            );
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('name')
                ->label('Nama Barang'),

            Tables\Columns\TextColumn::make('stock')
                ->label('Stok Saat Ini'),

            Tables\Columns\TextColumn::make('predicted_demand')
                ->label('Prediksi Bulan Depan')
                ->formatStateUsing(fn($state) => number_format($state, 0)),

            Tables\Columns\TextColumn::make('forecast_date')
                ->label('Bulan')
                ->date('F Y'),
        ];
    }

    protected function getTableHeading(): string
    {
        return '⚠️ Peringatan Stock Out Bulan Depan';
    }
}
