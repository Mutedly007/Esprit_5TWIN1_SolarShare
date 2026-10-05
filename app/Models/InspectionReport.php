<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class InspectionReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'rental_id',
        'type',
        'battery_level_percent',
        'physical_condition',
        'has_all_cables',
        'notes',
        'photos',
        'inspector_id',
        'is_validated',
        'validated_at',
    ];

    protected function casts(): array
    {
        return [
            'battery_level_percent' => 'integer',
            'has_all_cables' => 'boolean',
            'photos' => 'array',
            'is_validated' => 'boolean',
            'validated_at' => 'datetime',
        ];
    }

    /**
     * Relationship: Associated rental.
     */
    public function rental(): BelongsTo
    {
        return $this->belongsTo(Rental::class);
    }

    /**
     * Relationship: The user who performed / validated the inspection.
     */
    public function inspector(): BelongsTo
    {
        return $this->belongsTo(User::class, 'inspector_id');
    }

    /**
     * Readable type label.
     */
    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'check_in' => 'État des lieux de départ (Check-in)',
            'check_out' => 'État des lieux de retour (Check-out)',
            default => ucfirst(str_replace('_', ' ', $this->type)),
        };
    }

    /**
     * Readable condition label.
     */
    public function getConditionLabelAttribute(): string
    {
        return match ($this->physical_condition) {
            'excellent' => 'Excellent état',
            'good' => 'Bon état',
            'fair' => 'État moyen (traces d\'usure)',
            'damaged' => 'Endommagé / Dégradé',
            default => ucfirst($this->physical_condition),
        };
    }

    /**
     * Condition badge color class.
     */
    public function getConditionBadgeClassAttribute(): string
    {
        return match ($this->physical_condition) {
            'excellent' => 'badge-solar-success',
            'good' => 'badge-solar-info',
            'fair' => 'badge-solar-warning',
            'damaged' => 'badge-solar-danger',
            default => 'badge-solar-secondary',
        };
    }

    /**
     * Get array of full photo URLs.
     */
    public function getPhotoUrlsAttribute(): array
    {
        if (empty($this->photos) || ! is_array($this->photos)) {
            return [];
        }

        return array_map(function ($path) {
            if (Str::startsWith($path, ['http://', 'https://'])) {
                return $path;
            }
            return asset('storage/' . $path);
        }, $this->photos);
    }
}
