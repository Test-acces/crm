<?php

namespace App\Filament\Forms;

abstract class BaseForm
{
    /**
     * Get common validation rules
     */
    protected static function getCommonValidationRules(): array
    {
        return [
            'rules' => ['required', 'string', 'max:255'],
            'messages' => [
                'required' => 'This field is required.',
                'max' => 'This field cannot exceed :max characters.',
            ],
        ];
    }

    /**
     * Get common field configuration
     */
    protected static function configureField($field, array $options = []): mixed
    {
        $field->required($options['required'] ?? false)
              ->maxLength($options['max'] ?? 255)
              ->placeholder($options['placeholder'] ?? null);

        if (isset($options['rules'])) {
            $field->rules($options['rules']);
        }

        return $field;
    }

    /**
     * Get standard text input configuration
     */
    protected static function textInput(string $name, string $label, array $options = []): \Filament\Forms\Components\TextInput
    {
        return static::configureField(
            \Filament\Forms\Components\TextInput::make($name)->label($label),
            $options
        );
    }

    /**
     * Get standard email input configuration
     */
    protected static function emailInput(string $name = 'email', string $label = 'Email'): \Filament\Forms\Components\TextInput
    {
        return \Filament\Forms\Components\TextInput::make($name)
            ->label($label)
            ->email()
            ->required()
            ->unique(ignoreRecord: true)
            ->rules(['email:rfc,dns']);
    }

    /**
     * Get standard select configuration
     */
    protected static function selectInput(string $name, string $label, array $options): \Filament\Forms\Components\Select
    {
        $field = \Filament\Forms\Components\Select::make($name)
            ->label($label)
            ->options($options['options'] ?? $options)
            ->required($options['required'] ?? true)
            ->placeholder($options['placeholder'] ?? 'Select an option');

        if (isset($options['visible'])) {
            $field->visible($options['visible']);
        }

        return $field;
    }

    /**
     * Get standard textarea configuration
     */
    protected static function textareaInput(string $name, string $label, array $options = []): \Filament\Forms\Components\Textarea
    {
        return static::configureField(
            \Filament\Forms\Components\Textarea::make($name)->label($label),
            array_merge(['max' => 1000], $options)
        );
    }
}