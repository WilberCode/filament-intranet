<?php

namespace App\Filament\Personal\Resources\TimesheetResource\Pages;

use App\Exports\MyTimesheetExport;
use App\Exports\MyTimesheetPdf;
use App\Exports\MyTimesheetPdfTable;
use App\Filament\Personal\Resources\TimesheetResource;
use App\Imports\MyTimesheetImport;
use App\Models\Timesheet;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Carbon\Carbon;
use EightyNine\ExcelImport\ExcelImportAction;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Excel as ExcelType;
use Maatwebsite\Excel\Facades\Excel;
class ListTimesheets extends ListRecords
{
    protected static string $resource = TimesheetResource::class;

    protected function getHeaderActions(): array
    {
        $lastTimesheet = Timesheet::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->first();

        if ($lastTimesheet==null) {
            return [
                Action::make('inWork')
                ->label('Entrar Trabajar')
                ->color('success')
                ->requiresConfirmation()
                ->action(function(){
                      $user = Auth::user();
                      $timesheet = new Timesheet();
                      $timesheet->calendar_id = 2;
                      $timesheet->user_id = $user->id;
                      $timesheet->day_in = Carbon::now();
                      $timesheet->type = 'work';
                      $timesheet->save();

                      Notification::make()
                         ->title('Has empezado a trabajar')
                         ->success(1)
                         ->send();

                }),
                Actions\CreateAction::make(),
            ];

        }


        return [
            Action::make('inWork')
            ->label('Entrar Trabajar')
            ->color('success')
            ->visible(!$lastTimesheet->day_out == null)
            ->disabled($lastTimesheet->day_out == null)
            ->requiresConfirmation()
            ->action(function(){
                  $user = Auth::user();
                  $timesheet = new Timesheet();
                  $timesheet->calendar_id = 2;
                  $timesheet->user_id = $user->id;
                  $timesheet->day_in = Carbon::now();
                  $timesheet->type = 'work';
                  $timesheet->save();

                  Notification::make()
                  ->title('Has empezado a trabajar')
                  ->success(1)
                  ->send();

            }),
            Action::make('stopWork')
            ->label('Dejar de trabajar')
            ->color('success')
            ->visible($lastTimesheet->day_out == null && $lastTimesheet->type !== 'pause')
            ->disabled(!$lastTimesheet->day_out == null)
            ->requiresConfirmation()
            ->action(function() use($lastTimesheet) {

                $lastTimesheet->day_out = Carbon::now();
                $lastTimesheet->save();

                Notification::make()
                ->title('Has dejado de trabajar')
                ->info()
                ->send();

            }),


            Action::make('inPause')
            ->label('Pausar')
            ->color('info')
            ->visible($lastTimesheet->day_out == null && $lastTimesheet->type !== 'pause')
            ->disabled(!$lastTimesheet->day_out == null)
            ->requiresConfirmation()
            ->action(function() use($lastTimesheet) {

                $lastTimesheet->day_out = Carbon::now();
                $lastTimesheet->save();

                $timesheet = new Timesheet();
                $timesheet->calendar_id = 2;
                $timesheet->user_id = Auth::user()->id;
                $timesheet->day_in = Carbon::now();
                $timesheet->type = 'pause';
                $timesheet->save();

                Notification::make()
                ->title('Has comenzado a pausar')
                ->info()
                ->send();

            })
            ,
            Action::make('stopPause')
            ->label('Dejar de pausar')
            ->color('info')
            ->visible($lastTimesheet->day_out == null && $lastTimesheet->type == 'pause')
            ->disabled(!$lastTimesheet->day_out == null)
            ->requiresConfirmation()
            ->action(function() use($lastTimesheet) {

                $lastTimesheet->day_out = Carbon::now();
                $lastTimesheet->save();


                $timesheet = new Timesheet();
                $timesheet->calendar_id = 2;
                $timesheet->user_id = Auth::user()->id;
                $timesheet->day_in = Carbon::now();
                $timesheet->type = 'work';
                $timesheet->save();

                Notification::make()
                ->title('Has dejado de pausar')
                ->info()
                ->send();
            }),
            Actions\CreateAction::make(),
            ExcelImportAction::make()
            /* ->slideOver() */
            ->label("Importar")
            ->color("info")
            ->use(MyTimesheetImport::class),
            Action::make('createPDF')
            ->label("Crear PDF 1")
            ->color("warning")
            ->action(function() {
             return Excel::download(new MyTimesheetExport, 'timesheets.pdf', ExcelType::DOMPDF);

            }),
            Action::make('createPDF2')
            ->label("Crear PDF 2")
            ->color("warning")
            ->requiresConfirmation()
            ->url(
                fn () => route('generatePDF', ['user' => Auth::user()]),
                shouldOpenInNewTab: true,
            )
        ];
    }
}
