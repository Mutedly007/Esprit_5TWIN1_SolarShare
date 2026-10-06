<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOperatingSlotRequest extends FormRequest
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
            'collection_hub_id' => ['required', 'exists:collection_hubs,id'],
            'day_of_week' => ['required', 'string', 'in:lundi,mardi,mercredi,jeudi,vendredi,samedi,dimanche'],
            'open_time' => ['required', 'date_format:H:i'],
            'close_time' => ['required', 'date_format:H:i', 'after:open_time'],
            'is_active' => ['nullable', 'boolean'],
            'notes' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * Prepare data before validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active', true),
            'day_of_week' => strtolower($this->input('day_of_week', '')),
        ]);
    }

    /**
     * Custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'collection_hub_id.required' => 'Le hub de rattachement est obligatoire.',
            'collection_hub_id.exists' => 'Le hub sélectionné est introuvable.',
            'day_of_week.required' => 'Le jour de la semaine est obligatoire.',
            'day_of_week.in' => 'Le jour sélectionné n\'est pas valide (lundi à dimanche).',
            'open_time.required' => 'L\'heure d\'ouverture est obligatoire.',
            'open_time.date_format' => 'L\'heure d\'ouverture doit être au format HH:MM (ex: 08:30).',
            'close_time.required' => 'L\'heure de fermeture est obligatoire.',
            'close_time.date_format' => 'L\'heure de fermeture doit être au format HH:MM (ex: 18:00).',
            'close_time.after' => 'L\'heure de fermeture doit être postérieure à l\'heure d\'ouverture.',
            'notes.max' => 'Les notes ne peuvent pas dépasser 255 caractères.',
        ];
    }

    /**
     * Custom attributes.
     */
    public function attributes(): array
    {
        return [
            'collection_hub_id' => 'hub de collecte',
            'day_of_week' => 'jour de la semaine',
            'open_time' => 'heure d\'ouverture',
            'close_time' => 'heure de fermeture',
            'notes' => 'remarques',
        ];
    }
}
