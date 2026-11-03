<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    /**
     * Boot the model. Automatically create a slug if missing.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::saving(function (Category $category) {
            if (empty($category->slug) && ! empty($category->name)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    /**
     * Relationship: One category has many equipment.
     */
    public function equipment(): HasMany
    {
        return $this->hasMany(Equipment::class);
    }

    /**
     * Localized category name for default seeded slugs, falling back to stored name.
     */
    public function getLocalizedNameAttribute(): string
    {
        if (! empty($this->slug)) {
            $key = 'messages.marketplace.categories.' . $this->slug;
            $translated = __($key);
            if ($translated !== $key) {
                return $translated;
            }
        }

        return (string) $this->name;
    }

    /**
     * Localized category description for default seeded slugs, falling back to stored description.
     */
    public function getLocalizedDescriptionAttribute(): ?string
    {
        if (! empty($this->slug)) {
            $key = 'messages.marketplace.category_descriptions.' . $this->slug;
            $translated = __($key);
            if ($translated !== $key) {
                return $translated;
            }
        }

        return $this->description;
    }
}
