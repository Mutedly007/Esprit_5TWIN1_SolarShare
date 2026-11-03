<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EnergyKitReservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'energy_kit_id',
        'user_id',
        'start_date',
        'end_date',
        'days_count',
        'daily_rate',
        'total_price',
        'deposit_amount',
        'status',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'daily_rate' => 'decimal:2',
        'total_price' => 'decimal:2',
        'deposit_amount' => 'decimal:2',
    ];

    public function energyKit(): BelongsTo
    {
        return $this->belongsTo(EnergyKit::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'confirmed' => '<span class="badge bg-success">' . e(__('messages.kits.status_confirmed')) . '</span>',
            'active' => '<span class="badge bg-info text-dark">' . e(__('messages.kits.status_active')) . '</span>',
            'completed' => '<span class="badge bg-secondary">' . e(__('messages.kits.status_completed')) . '</span>',
            'cancelled' => '<span class="badge bg-danger">' . e(__('messages.kits.status_cancelled')) . '</span>',
            default => '<span class="badge bg-warning text-dark">' . e(__('messages.kits.status_pending')) . '</span>',
        };
    }
}
