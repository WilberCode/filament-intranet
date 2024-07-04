<?php

namespace App\Filament\Personal\Widgets;

use App\Models\Holiday;
use App\Models\Timesheet;
use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class PersonalWidgetStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
        /*
            Stat::make('Pending Holidays', $this->getPendingHoliday(Auth::user())), */
            Stat::make('Pending Holidays', $this->getData('pending')),
            Stat::make('Approved', $this->getData('approved')),
            Stat::make('Decline', $this->getData('decline')),
            Stat::make('Total Work', $this->getTotalWork()),
            Stat::make('Total Pause', $this->getTotalPuase()),
        ];
    }

 /*    protected function getPendingHoliday(User $user){
        $totalPendingHolidays =  Holiday::where('user_id',$user->id)->where('type', 'pending')->get()->count();
        return $totalPendingHolidays;
    } */

    protected function getData($type){
        $user = User::find(Auth::user()->id);
        return $user->holidays()->where('type', $type)->count();
    }

    protected function getTotalWork(){
        $user = User::find(Auth::user()->id);
        $timesheets =  Timesheet::where('user_id', $user->id)->where('type','work')->whereDate('created_at', Carbon::today())->get();
        $totalSeconds  = 0;
         foreach ($timesheets  as $timesheet) {
            $dayIn = Carbon::parse($timesheet->day_in);
            $dayOut = Carbon::parse($timesheet->day_out);
            $totalSeconds  += $dayOut->diffInSeconds($dayIn);
        }
        $totalDuration = gmdate("H:i:s", $totalSeconds);
         return   $totalDuration ;
    }
    protected function getTotalPuase(){
        $user = User::find(Auth::user()->id);
        $timesheets =  Timesheet::where('user_id', $user->id)->where('type','pause')->whereDate('created_at', Carbon::today())->get();
        $totalSeconds  = 0;
       foreach ($timesheets  as $timesheet) {
            $dayIn = Carbon::parse($timesheet->day_in);
            $dayOut = Carbon::parse($timesheet->day_out);
            $totalSeconds  += $dayOut->diffInSeconds($dayIn);
        }
        $totalDuration = gmdate("H:i:s", $totalSeconds);
         return   $totalDuration ;
    }
}
