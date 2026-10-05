<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Equipment extends Model
{
    /** @use HasFactory<\Database\Factories\EquipmentFactory> */
    use HasFactory;

    /**
     * The table associated with the model.
     * Note: Laravel pluralizes equipment to equipment.
     */
    protected $table = 'equipment';

    protected $fillable = [
        'user_id',
        'category_id',
        'name',
        'brand',
        'description',
        'image',
        'power_watts',
        'capacity_wh',
        'daily_rate',
        'deposit_amount',
        'is_available',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'is_available' => 'boolean',
            'daily_rate' => 'decimal:2',
            'deposit_amount' => 'decimal:2',
            'power_watts' => 'integer',
            'capacity_wh' => 'integer',
        ];
    }

    /**
     * Default status on creation is always 'pending' (Non traité).
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Equipment $equipment) {
            if (empty($equipment->status)) {
                $equipment->status = 'pending';
            }
        });
    }

    /**
     * Relationship: An equipment belongs to a Category.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relationship: An equipment belongs to a User (the lender).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: An equipment can have many rentals.
     */
    public function rentals(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Rental::class);
    }

    /**
     * Friendly status label in French as requested:
     * pending -> Non traité
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'approved' => 'Validé',
            'rejected' => 'Refusé',
            default => 'Non traité',
        };
    }

    /**
     * Get the full URL to the equipment image or null if not set.
     */
    public function getImageUrlAttribute(): ?string
    {
        if (empty($this->image)) {
            return null;
        }

        if (\Illuminate\Support\Str::startsWith($this->image, ['http://', 'https://'])) {
            return $this->image;
        }

        return asset('storage/' . $this->image);
    }
}
