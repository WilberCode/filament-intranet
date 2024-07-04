<?php

namespace App\Imports;

use App\Models\Calendar;
use App\Models\Timesheet;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MyTimesheetImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {

            $calendar = Calendar::where('name', $row['calendar'])->first();

            if ($calendar !== null) {
                Timesheet::create([
                    'calendar_id' => $calendar->id,
                    'user_id' => Auth::user()->id,
                    'type' => $row['tipo'],
                    'day_in' => $row['entrada'],
                    'day_out' => $row['salida'],
                ]);
            }

        }
    }
}
