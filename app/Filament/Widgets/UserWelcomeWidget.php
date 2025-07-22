<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;

class UserWelcomeWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        $user = Auth::user();

        return [
            Stat::make('Nama', $user->name)
                ->color('primary'),

            Stat::make('Role', $user->getRoleName())
                ->color('info'),

            Stat::make('Divisi', $user->division->name ?? '-')
                ->color('success'),
        ];
    }
}