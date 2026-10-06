<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CollectionHub extends Model
{
    /** @use HasFactory<\Database\Factories\CollectionHubFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'city',
        'postal_code',
        'contact_phone',
        'max_capacity',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'max_capacity' => 'integer',
        ];
    }

    /**
     * Relationship: 1 Hub has many Operating Slots (1-N)
     */
    public function operatingSlots(): HasMany
    {
        return $this->hasMany(OperatingSlot::class);
    }

    /**
     * Active operating slots ordered by standard week days.
     */
    public function activeOperatingSlots(): HasMany
    {
        return $this->operatingSlots()->where('is_active', true);
    }

    /**
     * Scope to only active collection hubs.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to filter by city.
     */
    public function scopeCity(Builder $query, ?string $city): Builder
    {
        if (! empty($city)) {
            return $query->where('city', $city);
        }

        return $query;
    }

    /**
     * Get the current day in French lower-case.
     */
    public static function getCurrentFrenchDay(): string
    {
        $days = [
            0 => 'dimanche',
            1 => 'lundi',
            2 => 'mardi',
            3 => 'mercredi',
            4 => 'jeudi',
            5 => 'vendredi',
            6 => 'samedi',
        ];

        return $days[Carbon::now()->dayOfWeek] ?? 'lundi';
    }

    /**
     * Check if this hub is open today.
     */
    public function isOpenToday(): bool
    {
        if (! $this->is_active) {
            return false;
        }

        $today = self::getCurrentFrenchDay();

        return $this->operatingSlots
            ->where('is_active', true)
            ->contains(fn ($slot) => strtolower($slot->day_of_week) === $today);
    }

    /**
     * Get today's operating slot if open.
     */
    public function getTodaySlot(): ?OperatingSlot
    {
        $today = self::getCurrentFrenchDay();

        return $this->operatingSlots
            ->where('is_active', true)
            ->first(fn ($slot) => strtolower($slot->day_of_week) === $today);
    }
}
