<?php

namespace App\Filament\Widgets;

use App\Models\Requests;
use Illuminate\Support\Carbon;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;

class StatsDashboard extends BaseWidget
{
    protected function getStats(): array
    {
        $user = Auth::user();
        $divId = $user->div_id;
        $pending = Requests::where('status', 'pending')
            ->whereHas('employee', fn($query) => $query->where('div_id', $divId))
            ->count();
        $week = Requests::where('status', 'accepted')
            ->whereHas('employee', fn($query) => $query->where('div_id', $divId))
            ->whereBetween('created_at', [
                Carbon::now()->subWeek(),
                Carbon::now(),
            ])->count();
        return [
            Stat::make('Permintaan Menunggu Persetujuan', $pending),
            Stat::make('Permintaan Disetujui Minggu Ini', $week),
            Stat::make('Average time on page', '3:12'),
        ];
    }
}
