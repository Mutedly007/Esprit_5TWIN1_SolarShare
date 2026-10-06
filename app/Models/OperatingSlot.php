<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OperatingSlot extends Model
{
    /** @use HasFactory<\Database\Factories\OperatingSlotFactory> */
    use HasFactory;

    protected $fillable = [
        'collection_hub_id',
        'day_of_week',
        'open_time',
        'close_time',
        'is_active',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * Relationship: Operating slot belongs to a collection hub.
     */
    public function hub(): BelongsTo
    {
        return $this->belongsTo(CollectionHub::class, 'collection_hub_id');
    }

    /**
     * Capitalized French day name.
     */
    public function getFormattedDayAttribute(): string
    {
        return ucfirst($this->day_of_week);
    }

    /**
     * Human-readable hours span (e.g. 08:30 - 18:00).
     */
    public function getFormattedHoursAttribute(): string
    {
        return "{$this->open_time} - {$this->close_time}";
    }
}
