<?php

namespace App\Filament\Forms;

use App\Models\Client;
use Filament\Schemas;

class ContactForm extends BaseForm
{
    public static function schema(): array
    {
        $user = auth()->user();
        $clientsQuery = Client::query();

        if ($user && !$user->canSeeAllClients()) {
            $clientsQuery->where('user_id', $user->id);
        }

        $clients = $clientsQuery->pluck('name', 'id')->toArray();

        return [
            static::selectInput('client_id', 'Client', $clients)
                ->required(),

            static::textInput('name', 'Full Name', [
                'required' => true,
                'rules' => ['string', 'regex:/^[\p{L}\s\-\.\']+$/u', 'min:2'],
                'placeholder' => 'Enter contact full name',
            ]),

            static::emailInput('email', 'Email Address'),

            static::textInput('phone', 'Phone Number', [
                'required' => false,
                'rules' => ['nullable', 'regex:/^(\+?\d{1,3}[- ]?)?\d{10,}$/'],
                'placeholder' => '+1 234 567 8900',
            ]),

            static::textInput('position', 'Position/Title', [
                'required' => false,
                'placeholder' => 'e.g. CEO, Manager, Developer',
            ]),

            static::textareaInput('notes', 'Notes', [
                'required' => false,
                'placeholder' => 'Additional notes about the contact',
            ]),
        ];
    }
}