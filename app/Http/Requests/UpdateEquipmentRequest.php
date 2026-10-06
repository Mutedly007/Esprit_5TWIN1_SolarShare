<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEquipmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (! auth()->check() || ! auth()->user()->isLender()) {
            return false;
        }

        $equipment = $this->route('equipment');
        if ($equipment instanceof \App\Models\Equipment) {
            return $equipment->user_id === auth()->id();
        }

        if (is_numeric($equipment)) {
            $found = \App\Models\Equipment::find($equipment);
            return $found ? $found->user_id === auth()->id() : false;
        }

        return true;
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
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'brand' => ['required', 'string', 'min:2', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:5120'],
            'power_watts' => ['nullable', 'numeric', 'min:0', 'max:50000'],
            'capacity_wh' => ['nullable', 'numeric', 'min:0', 'max:100000'],
            'daily_rate' => ['required', 'numeric', 'min:0.01', 'max:100000'],
            'deposit_amount' => ['required', 'numeric', 'min:0', 'max:500000'],
            'is_available' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Le nom de l\'équipement est obligatoire.',
            'name.min' => 'Le nom de l\'équipement doit comporter au moins 3 caractères.',
            'name.max' => 'Le nom de l\'équipement ne peut pas dépasser 255 caractères.',
            'category_id.required' => 'Veuillez sélectionner une catégorie.',
            'category_id.exists' => 'La catégorie sélectionnée n\'existe pas dans notre base.',
            'brand.required' => 'La marque ou le fabricant est obligatoire.',
            'brand.min' => 'La marque doit comporter au moins 2 caractères.',
            'brand.max' => 'La marque ne peut pas dépasser 255 caractères.',
            'daily_rate.required' => 'Le tarif journalier de location est obligatoire.',
            'daily_rate.numeric' => 'Le tarif journalier doit être un montant numérique valide.',
            'daily_rate.min' => 'Le tarif journalier doit être d\'au moins 0.01 DT.',
            'daily_rate.max' => 'Le tarif journalier est trop élevé.',
            'deposit_amount.required' => 'Le montant de la caution est obligatoire.',
            'deposit_amount.numeric' => 'Le montant de la caution doit être un montant numérique valide.',
            'deposit_amount.min' => 'Le montant de la caution ne peut pas être négatif.',
            'deposit_amount.max' => 'Le montant de la caution est trop élevé.',
            'power_watts.numeric' => 'La puissance doit être un nombre exprimé en Watts.',
            'power_watts.min' => 'La puissance ne peut pas être négative.',
            'power_watts.max' => 'La puissance spécifiée dépasse la limite autorisée.',
            'capacity_wh.numeric' => 'La capacité de stockage doit être un nombre exprimé en Wh.',
            'capacity_wh.min' => 'La capacité ne peut pas être négative.',
            'capacity_wh.max' => 'La capacité spécifiée dépasse la limite autorisée.',
            'image.image' => 'Le fichier sélectionné doit être une image.',
            'image.mimes' => 'Formats d\'image autorisés : JPG, JPEG, PNG, WebP, SVG.',
            'image.max' => 'L\'image ne doit pas dépasser 5 Mo.',
            'description.max' => 'La description ne peut pas dépasser 2000 caractères.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'nom de l\'équipement',
            'category_id' => 'catégorie',
            'brand' => 'marque',
            'daily_rate' => 'tarif par jour',
            'deposit_amount' => 'montant de la caution',
            'power_watts' => 'puissance en watts',
            'capacity_wh' => 'capacité en watt-heures',
            'image' => 'photo de l\'équipement',
            'description' => 'description',
        ];
    }
}
