<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Rental extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'equipment_id',
        'start_date',
        'end_date',
        'total_price',
        'status',
        'renter_notes',
        'cancellation_reason',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'total_price' => 'decimal:2',
        ];
    }

    /**
     * Relationship: The borrower (locataire/emprunteur).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: The rented equipment.
     */
    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class);
    }

    /**
     * Relationship: Inspection reports (1 - N: check_in and check_out).
     */
    public function inspectionReports(): HasMany
    {
        return $this->hasMany(InspectionReport::class);
    }

    /**
     * Inspection report: Check-in (départ).
     */
    public function checkInReport(): HasOne
    {
        return $this->hasOne(InspectionReport::class)->where('type', 'check_in');
    }

    /**
     * Inspection report: Check-out (retour).
     */
    public function checkOutReport(): HasOne
    {
        return $this->hasOne(InspectionReport::class)->where('type', 'check_out');
    }

    /**
     * Number of days between start_date and end_date (inclusive).
     */
    public function getDurationDaysAttribute(): int
    {
        if (! $this->start_date || ! $this->end_date) {
            return 1;
        }

        $start = Carbon::parse($this->start_date);
        $end = Carbon::parse($this->end_date);

        return max(1, $start->diffInDays($end) + 1);
    }

    /**
     * Calculate price for dates and daily rate.
     */
    public static function calculatePrice(float $dailyRate, $startDate, $endDate): float
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        $days = max(1, $start->diffInDays($end) + 1);

        return round($days * $dailyRate, 2);
    }

    /**
     * Human-friendly status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'En attente',
            'confirmed' => 'Confirmée',
            'active' => 'En cours (Prêt actif)',
            'completed' => 'Terminée',
            'cancelled' => 'Annulée',
            default => ucfirst($this->status),
        };
    }

    /**
     * Status badge styling classes.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'badge-solar-warning',
            'confirmed' => 'badge-solar-info',
            'active' => 'badge-solar-primary',
            'completed' => 'badge-solar-success',
            'cancelled' => 'badge-solar-danger',
            default => 'badge-solar-secondary',
        };
    }

    /**
     * Permissions & capabilities.
     */
    public function isLender(?User $user): bool
    {
        if (! $user) {
            return false;
        }

        return $this->equipment && $this->equipment->user_id === $user->id;
    }

    public function isRenter(?User $user): bool
    {
        if (! $user) {
            return false;
        }

        return $this->user_id === $user->id;
    }

    public function canBeManagedBy(?User $user): bool
    {
        if (! $user) {
            return false;
        }

        return $user->isAdmin() || $this->isLender($user) || $this->isRenter($user);
    }

    public function canBeEdited(): bool
    {
        return in_array($this->status, ['pending', 'confirmed']);
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending', 'confirmed']);
    }

    public function canDoCheckIn(): bool
    {
        return $this->status === 'confirmed'
            && (! $this->checkInReport || ! $this->checkInReport->is_validated);
    }

    public function canDoCheckOut(): bool
    {
        return $this->status === 'active'
            && $this->checkInReport
            && $this->checkInReport->is_validated
            && (! $this->checkOutReport || ! $this->checkOutReport->is_validated);
    }
}
