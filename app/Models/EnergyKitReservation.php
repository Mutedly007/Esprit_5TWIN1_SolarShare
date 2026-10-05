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
            'confirmed' => '<span class="badge bg-success">Confirmée</span>',
            'active' => '<span class="badge bg-info text-dark">En cours</span>',
            'completed' => '<span class="badge bg-secondary">Terminée</span>',
            'cancelled' => '<span class="badge bg-danger">Annulée</span>',
            default => '<span class="badge bg-warning text-dark">En attente</span>',
        };
    }
}
