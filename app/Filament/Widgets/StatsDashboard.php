<?php

namespace App\Filament\Widgets;

use App\Models\Requests;
use App\Models\StockOpname;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsDashboard extends BaseWidget
{
    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        $user = Auth::user();
        $divId = $user->div_id;
        $divisi = $user->division->name ?? '';

        $pending = Requests::where('status', 'pending')
            ->whereHas('employee', fn($query) => $query->where('div_id', $divId))
            ->count();

        $week = Requests::where('status', 'accepted')
            ->whereHas('employee', fn($query) => $query->where('div_id', $divId))
            ->whereBetween('created_at', [
                Carbon::now()->subWeek(),
                Carbon::now(),
            ])->count();

        $opnameBerjalan = StockOpname::where('div_id', $divId)
            ->whereNotIn('opname_status', ['Completed', 'Cancelled'])
            ->exists();

        return [
            Stat::make('Permintaan Menunggu Persetujuan', $pending),
            Stat::make('Permintaan Disetujui Minggu Ini', $week),
            Stat::make('Status Stock Opname', $opnameBerjalan ? 'Sedang Berlangsung' : 'Tidak Ada')
                ->description($opnameBerjalan ? 'Stock opname masih berlangsung' : 'Tidak ada proses opname')
                ->color($opnameBerjalan ? 'warning' : 'success'),
        ];
    }
}
