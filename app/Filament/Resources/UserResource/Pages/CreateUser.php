<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (empty($data['password'])) {
            $password = Str::random(12);
            $data['password'] = bcrypt($password);

            // Send email with password
            Mail::raw("Votre compte a été créé. Email: {$data['email']}\nMot de passe: {$password}", function ($message) use ($data) {
                $message->to($data['email'])->subject('Votre compte CRM');
            });
        }

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}