<?php

namespace App\Filament\Resources\HolidayResource\Pages;

use App\Filament\Resources\HolidayResource;
use App\Mail\HolidayApproved;
use App\Mail\HolidayDecline;
use App\Models\User;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class EditHoliday extends EditRecord
{
    protected static string $resource = HolidayResource::class;
/*
    protected function afterSave( ){ */
    /*     $holiday = $this->getRecord();

        $user =  User::find($holiday['user_id']);
        $data =  array(
            'name' => $user->name,
            'email' => $user->email,
            'day' => $holiday['day'],
        ); */
       /*  if ($holiday["type"] == "approved") {
             Mail::to($user)->send(new HolidayApproved($data));
        } elseif ($holiday['type'] == 'decline') {
             Mail::to($user)->send(new HolidayDecline($data));
        } else {
        } */
/*     } */

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
      /*   dd($data); */
             /*    dd($record);*/
        $record->update($data);
        $user =  User::find($record->user_id);
        $data =  array(
            'name' => $user->name,
            'email' => $user->email,
            'day' => $record->day,
        );
        // Send email aproved
        if ($record->type == "approved") {
            Mail::to($user)->send(new HolidayApproved($data));
            Notification::make()
            ->title('Solicitud de vacaciones')
            ->body("El día ".$data['day']." esta aprobado")
            ->sendToDatabase($user);
        }
        if($record->type == 'decline') {
            Mail::to($user)->send(new HolidayDecline($data));
            Notification::make()
            ->title('Solicitud de vacaciones')
            ->body("El día ".$data['day']." esta rechazada")
            ->sendToDatabase($user);
        }



        return $record;

   }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
