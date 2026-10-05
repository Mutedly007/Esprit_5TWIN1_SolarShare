<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class EnergyKit extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'description',
        'target_use',
        'discount_percentage',
        'is_active',
        'image',
    ];

    protected function casts(): array
    {
        return [
            'discount_percentage' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Auto slug generation on create/update if not provided.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::saving(function (EnergyKit $kit) {
            if (empty($kit->slug) && ! empty($kit->title)) {
                $base = Str::slug($kit->title);
                $slug = $base;
                $i = 1;
                while (static::where('slug', $slug)->where('id', '!=', $kit->id ?? 0)->exists()) {
                    $slug = $base . '-' . $i++;
                }
                $kit->slug = $slug;
            }
        });
    }

    /**
     * Relationship: An EnergyKit belongs to many Equipment (N - N via pivot).
     */
    public function equipment(): BelongsToMany
    {
        return $this->belongsToMany(Equipment::class, 'energy_kit_equipment')
            ->withPivot(['quantity', 'options'])
            ->withTimestamps();
    }

    /**
     * Relationship: The creator/manager of the kit.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get route key name for binding.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Raw total daily rate before discount (sum of each equipment rate * quantity).
     */
    public function getRawDailyRateAttribute(): float
    {
        return round($this->equipment->sum(function ($item) {
            $qty = $item->pivot->quantity ?? 1;
            return ((float) $item->daily_rate) * $qty;
        }), 2);
    }

    /**
     * Discounted daily rate applying the kit discount percentage.
     */
    public function getDiscountedDailyRateAttribute(): float
    {
        $raw = $this->raw_daily_rate;
        $discount = (float) $this->discount_percentage;

        if ($discount <= 0) {
            return $raw;
        }

        return round($raw * (1 - ($discount / 100)), 2);
    }

    /**
     * Alias for discounted_daily_rate.
     */
    public function getDailyRateAfterDiscountAttribute(): float
    {
        return $this->discounted_daily_rate;
    }

    /**
     * Amount saved per day thanks to the kit discount.
     */
    public function getDailySavingsAttribute(): float
    {
        return round($this->raw_daily_rate - $this->discounted_daily_rate, 2);
    }

    /**
     * Total deposit amount (sum of each item deposit * quantity).
     */
    public function getTotalDepositAttribute(): float
    {
        return round($this->equipment->sum(function ($item) {
            $qty = $item->pivot->quantity ?? 1;
            return ((float) $item->deposit_amount) * $qty;
        }), 2);
    }

    /**
     * Total combined power in Watts.
     */
    public function getTotalPowerWattsAttribute(): int
    {
        return (int) $this->equipment->sum(function ($item) {
            $qty = $item->pivot->quantity ?? 1;
            return ($item->power_watts ?? 0) * $qty;
        });
    }

    /**
     * Total combined battery capacity in Wh.
     */
    public function getTotalCapacityWhAttribute(): int
    {
        return (int) $this->equipment->sum(function ($item) {
            $qty = $item->pivot->quantity ?? 1;
            return ($item->capacity_wh ?? 0) * $qty;
        });
    }

    /**
     * Check if every piece of equipment in the kit is currently available.
     */
    public function getIsAllAvailableAttribute(): bool
    {
        if ($this->equipment->isEmpty()) {
            return false;
        }

        return $this->equipment->every(fn($item) => (bool) $item->is_available);
    }

    /**
     * Friendly label in French for target use.
     */
    public function getTargetUseLabelAttribute(): string
    {
        return match ($this->target_use) {
            'camping' => 'Camping & Plein Air',
            'chantier' => 'Chantier & Travaux',
            'urgence' => 'Secours & Urgence',
            'vanlife' => 'Vanlife & Voyage',
            'evenement' => 'Événementiel & Fêtes',
            default => ucfirst($this->target_use),
        };
    }

    /**
     * Target use badge styling.
     */
    public function getTargetUseBadgeStyleAttribute(): string
    {
        return match ($this->target_use) {
            'camping' => 'background: #DCFCE7; color: #166534; border: 1px solid #BBF7D0;',
            'chantier' => 'background: #FEF3C7; color: #92400E; border: 1px solid #FCD34D;',
            'urgence' => 'background: #FEE2E2; color: #991B1B; border: 1px solid #FECACA;',
            'vanlife' => 'background: #E0F2FE; color: #0369A1; border: 1px solid #BAE6FD;',
            'evenement' => 'background: #F3E8FF; color: #6B21A8; border: 1px solid #E9D5FF;',
            default => 'background: #F1F5F9; color: #475569; border: 1px solid #E2E8F0;',
        };
    }

    /**
     * Full URL for the pack image.
     */
    public function getImageUrlAttribute(): ?string
    {
        if (empty($this->image)) {
            return null;
        }

        if (Str::startsWith($this->image, ['http://', 'https://'])) {
            return $this->image;
        }

        return asset('storage/' . $this->image);
    }

    /**
     * Relationship: An energy kit can have multiple reservations.
     */
    public function reservations(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(EnergyKitReservation::class);
    }
}
