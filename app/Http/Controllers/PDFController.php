<?php

namespace App\Http\Controllers;

use App\Models\Timesheet;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Calculation\DateTimeExcel\Time;

class PDFController extends Controller
{
    public function generatePDF(User $user)
    {
        $timesheets =  Timesheet::where('user_id', $user->id)->get();
/*
        dd($data); */
        $pdf = Pdf::loadView('exports.timesheet-pdf-table', compact('timesheets'));
        return $pdf->download('timesheet.pdf');
    }
}
