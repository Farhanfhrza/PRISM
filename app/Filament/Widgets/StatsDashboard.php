<?php

namespace App\Filament\Widgets;

use App\Models\Requests;
use Illuminate\Support\Carbon;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsDashboard extends BaseWidget
{
    protected function getStats(): array
    {
        $pending = Requests::where('status', 'pending')->count();
        $week = Requests::where('status', 'accepted')
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
