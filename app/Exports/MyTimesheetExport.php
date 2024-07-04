<?php

namespace App\Exports;

use App\Models\Timesheet;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MyTimesheetExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Timesheet::where('user_id', auth()->id())->get();

    }
    public function headings(): array
    {
        return [
            'ID',
            'User ID',
            'Calendar ID',
            'Day In',
            'Day Out',
            'Type',
            'Created At',
            'Updated At',
        ];
    }
}
