<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCollectionHubRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'address' => ['required', 'string', 'min:5', 'max:255'],
            'city' => ['required', 'string', 'min:2', 'max:100'],
            'postal_code' => ['required', 'string', 'regex:/^[0-9]{4,5}$/'],
            'contact_phone' => ['required', 'string', 'min:8', 'max:30'],
            'max_capacity' => ['required', 'integer', 'min:1', 'max:500'],
            'description' => ['nullable', 'string', 'max:2000'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active', true),
        ]);
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Le nom du hub de collecte est obligatoire.',
            'name.min' => 'Le nom doit comporter au moins 3 caractères.',
            'name.max' => 'Le nom ne peut pas dépasser 255 caractères.',
            'address.required' => 'L\'adresse physique du hub est obligatoire.',
            'address.min' => 'L\'adresse doit comporter au moins 5 caractères.',
            'city.required' => 'La ville est obligatoire.',
            'postal_code.required' => 'Le code postal est obligatoire.',
            'postal_code.regex' => 'Le code postal doit être composé de 4 ou 5 chiffres.',
            'contact_phone.required' => 'Le numéro de téléphone de contact est obligatoire.',
            'contact_phone.min' => 'Le numéro de contact doit comporter au moins 8 caractères.',
            'max_capacity.required' => 'La capacité maximale de stockage est obligatoire.',
            'max_capacity.integer' => 'La capacité maximale doit être un nombre entier.',
            'max_capacity.min' => 'La capacité maximale doit être d\'au moins 1 équipement.',
            'max_capacity.max' => 'La capacité maximale ne peut pas dépasser 500 équipements.',
            'description.max' => 'La description ne peut pas dépasser 2000 caractères.',
        ];
    }

    /**
     * Custom attributes.
     */
    public function attributes(): array
    {
        return [
            'name' => 'nom du hub',
            'address' => 'adresse',
            'city' => 'ville',
            'postal_code' => 'code postal',
            'contact_phone' => 'téléphone de contact',
            'max_capacity' => 'capacité maximale',
            'description' => 'description',
        ];
    }
}
