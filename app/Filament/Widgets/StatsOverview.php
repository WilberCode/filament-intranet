<?php

namespace App\Filament\Widgets;

use App\Models\Holiday;
use App\Models\Timesheet;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalEmployees = User::count();
        $totalHolidays = Holiday::count();
        $totalTimesheets  = Timesheet ::count();
        return [
            Stat::make('Employees ',    $totalEmployees)
            ->description('32k increase')
            ->descriptionIcon('heroicon-m-arrow-trending-up'),
            Stat::make('Pending Holidays', $totalHolidays),
            Stat::make('Timesheets',  $totalTimesheets),
            Stat::make('Unique views', '192.1k')
            ->description('32k increase')
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->chart([7, 2, 10, 26, 15, 4, 17])
            ->color('success'),
        ];
    }
}
