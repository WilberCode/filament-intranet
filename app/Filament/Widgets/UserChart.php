<?php

namespace App\Filament\Widgets;

use App\Models\Holiday;
use Filament\Widgets\ChartWidget;

class UserChart extends ChartWidget
{
    protected static ?string $heading = 'Chart';

    protected function getData(): array
    {
        $activeFilter = $this->filter;
        return [
            'datasets' => [
                [
                    'label' => 'Requests',
                    'data' =>  $this->getHolidaysDate(),
                ],
            ],
         /*    'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'], */
            'labels' => [ $activeFilter  ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
  /*   protected function getUser(){
        return [0, 10, 5, 2, 21, 32, 45, 74, 65, 45, 77, 89];
    } */
    protected function getHolidaysDate(){
        $holidaysDate = Holiday::select('day')->get();
         $holidaysCount = [];
         foreach ($holidaysDate as $holiday) {
                $month = date('F', strtotime($holiday->day));
                if (isset($holidaysCount[$month])) {
                    $holidaysCount[$month] += 1;
                } else {
                    $holidaysCount[$month] = 1;
                }
        }
       return $holidaysCount;
    }
    protected function getFilters(): ?array
    {
        return [
            'January' => 'January',
            'June' => 'June',
            'July' => 'July',
            'August' => 'August',
        ];
    }
}
