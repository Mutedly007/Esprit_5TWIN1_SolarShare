<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'password_set_at',
        'solar_role',
        'role',
        'provider',
        'provider_id',
        'avatar',
    ];

    /**
     * Relationship: A user can own many equipment.
     */
    public function equipment(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Equipment::class);
    }

    /**
     * Relationship: Rentals created by this user as borrower/renter.
     */
    public function rentals(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Rental::class);
    }

    /**
     * Relationship: Rentals received for equipment owned by this user.
     */
    public function lentRentals(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(Rental::class, Equipment::class);
    }

    /**
     * Check if the user is an administrator.
     */
    public function isAdmin(): bool
    {
        return strtolower((string) ($this->role ?? '')) === 'admin'
            || strtolower((string) ($this->solar_role ?? '')) === 'admin';
    }

    /**
     * Check if the user has the lender role (lend, both, or admin).
     */
    public function isLender(): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        $role = strtolower((string) ($this->solar_role ?? $this->role ?? ''));
        return in_array($role, ['lend', 'both'], true);
    }

    /**
     * Check if the user has the renter role (rent, both, or admin).
     */
    public function isRenter(): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        $role = strtolower((string) ($this->solar_role ?? $this->role ?? ''));
        return in_array($role, ['rent', 'both'], true);
    }

    /**
     * Check if user can reserve/rent equipment (renter, both, or admin).
     */
    public function canReserveEquipment(): bool
    {
        return $this->isRenter();
    }

    /**
     * Get human-readable label for solar role.
     */
    public function getSolarRoleLabelAttribute(): string
    {
        $role = strtolower((string) ($this->solar_role ?? $this->role ?? ''));
        return match ($role) {
            'admin' => 'Administrateur',
            'lend' => 'Prêteur (Lend)',
            'rent' => 'Emprunteur (Rent)',
            'both' => 'Mixte (Lend & Rent)',
            default => 'Non défini',
        };
    }

    /**
     * Check if user has explicitly set their own password.
     */
    public function hasSetPassword(): bool
    {
        return ! empty($this->password_set_at);
    }

    /**
     * Check if user has an avatar.
     */
    public function hasAvatar(): bool
    {
        return ! empty($this->avatar);
    }

    /**
     * Get avatar full URL (supports external OAuth URLs and local storage).
     */
    public function getAvatarUrlAttribute(): ?string
    {
        if (empty($this->avatar)) {
            return null;
        }

        if (str_starts_with($this->avatar, 'http://') || str_starts_with($this->avatar, 'https://')) {
            return $this->avatar;
        }

        return asset('storage/' . $this->avatar);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password_set_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
