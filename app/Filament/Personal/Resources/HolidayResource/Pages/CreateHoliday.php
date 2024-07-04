<?php

namespace App\Filament\Personal\Resources\HolidayResource\Pages;

use App\Filament\Personal\Resources\HolidayResource;
use App\Mail\HolidayPending;
use App\Models\User;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class CreateHoliday extends CreateRecord
{
    protected static string $resource = HolidayResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = Auth::user();
        $data['user_id'] = $user->id;
        $data['type'] = 'pending';
        $userAdmin = User::find(2);
        $dataToSend = array(
            'day' => $data['day'],
            'name' =>  $user->name,
            'email' =>  $user->email,
        );
        Mail::to($userAdmin)->send(new HolidayPending($dataToSend));

     /*    Notification::make()
        ->title('Solicitud de vacaciones')
        ->body("El día ".$data['day']." esta pendiente de aprobar")
        ->success()
        ->send(); */
        $recipient = auth()->user();
        Notification::make()
        ->title('Solicitud de vacaciones')
        ->body("El día ".$data['day']." esta pendiente de aprobar")
        ->sendToDatabase($recipient);

        return $data;
    }

}

