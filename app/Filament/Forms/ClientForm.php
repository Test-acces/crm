<?php

namespace App\Filament\Forms;

use Filament\Schemas;

class ClientForm extends BaseForm
{
    public static function schema(): array
    {
        return [
            static::textInput('name', 'Client Name', [
                'required' => true,
                'rules' => ['string', 'regex:/^[\p{L}\s\-\.\']+$/u', 'min:2'],
                'placeholder' => 'Enter client name',
                'helperText' => 'Le nom du client doit contenir au moins 2 caractères.',
            ]),

            static::emailInput('email', 'Email Address'),

            static::textInput('phone', 'Phone Number', [
                'required' => false,
                'rules' => ['nullable', 'regex:/^(\+?\d{1,3}[- ]?)?\d{10,}$/'],
                'placeholder' => '+1 234 567 8900',
            ]),

            static::textareaInput('address', 'Address', [
                'required' => false,
                'max' => 500,
                'placeholder' => 'Enter client address',
            ]),

            static::selectInput('status', 'Status', \App\Models\ClientStatus::options())->default('active'),

            static::textareaInput('notes', 'Notes', [
                'required' => false,
                'placeholder' => 'Additional notes about the client',
            ]),

            static::selectInput('user_id', 'Assigned User', [
                'options' => \App\Models\User::pluck('name', 'id')->toArray(),
                'visible' => fn () => auth()->user()->canManageUsers() || auth()->user()->isAdmin(),
                'required' => false,
            ]),
        ];
    }
}