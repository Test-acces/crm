<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('update', $this->route('client'));
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $clientId = $this->route('client')?->id ?? $this->route('record')?->id;

        return [
            'name' => ['required', 'string', 'max:255', 'min:2', 'regex:/^[\p{L}\s\-\.\']+$/u'],
            'email' => [
                'required',
                'email:rfc,dns',
                Rule::unique('clients', 'email')->ignore($clientId)
            ],
            'phone' => ['nullable', 'string', 'regex:/^(\+?\d{1,3}[- ]?)?\d{10,}$/'],
            'address' => ['nullable', 'string', 'max:500'],
            'status' => ['required', 'in:active,inactive'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The client name is required.',
            'name.regex' => 'The client name may only contain letters and spaces.',
            'email.unique' => 'This email address is already in use by another client.',
            'phone.regex' => 'Please enter a valid phone number.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => 'client name',
            'email' => 'email address',
            'phone' => 'phone number',
            'address' => 'address',
            'status' => 'status',
            'notes' => 'notes',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => trim($this->name),
            'email' => strtolower(trim($this->email)),
        ]);
    }
}