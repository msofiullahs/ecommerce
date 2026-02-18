<?php

namespace App\Models;

use App\Enums\PromoType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Translatable\HasTranslations;

class Promo extends Model
{
    use HasTranslations, SoftDeletes;

    public array $translatable = ['name', 'description'];

    protected $fillable = [
        'code', 'name', 'description', 'type', 'value',
        'minimum_order', 'maximum_discount', 'usage_limit',
        'usage_count', 'per_user_limit', 'starts_at', 'expires_at', 'is_active',
    ];

    protected $casts = [
        'type' => PromoType::class,
        'value' => 'decimal:2',
        'minimum_order' => 'decimal:2',
        'maximum_discount' => 'decimal:2',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'promo_user')->withTimestamps();
    }

    public function isValid(): bool
    {
        return $this->is_active
            && ($this->starts_at === null || $this->starts_at->isPast())
            && ($this->expires_at === null || $this->expires_at->isFuture())
            && ($this->usage_limit === null || $this->usage_count < $this->usage_limit);
    }

    public function hasUserExceededLimit(User $user): bool
    {
        if ($this->per_user_limit === null) {
            return false;
        }
        return $this->users()->where('user_id', $user->id)->count() >= $this->per_user_limit;
    }

    public function calculateDiscount(float $subtotal): float
    {
        if ($this->minimum_order !== null && $subtotal < $this->minimum_order) {
            return 0;
        }

        $discount = match ($this->type) {
            PromoType::PERCENTAGE => $subtotal * ($this->value / 100),
            PromoType::FIXED => $this->value,
        };

        if ($this->maximum_discount !== null) {
            $discount = min($discount, $this->maximum_discount);
        }

        return round(min($discount, $subtotal), 2);
    }
}
