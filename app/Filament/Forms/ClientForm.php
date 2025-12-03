<?php

namespace App\Filament\Forms;

use App\Models\ClientStatus;
use Filament\Schemas;

class ClientForm extends BaseForm
{
    public static function schema(): array
    {
        return [
            static::textInput('name', 'Client Name', [
                'required' => true,
                'rules' => ['string', 'regex:/^[a-zA-Z\s]+$/', 'min:2'],
                'placeholder' => 'Enter client name',
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

            static::selectInput('status', 'Status', ClientStatus::options())
                ->default(ClientStatus::ACTIVE->value),

            static::textareaInput('notes', 'Notes', [
                'required' => false,
                'placeholder' => 'Additional notes about the client',
            ]),
        ];
    }
}