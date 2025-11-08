<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'duration_months',
        'price',
        'currency',
        'problem_limit',
        'applicant_limit',
        'ai_verification_limit',
        'ai_chat_limit',
        'features',
        'is_active',
        'is_featured',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'float',
        'duration_months' => 'integer',
        'problem_limit' => 'integer',
        'applicant_limit' => 'integer',
        'ai_verification_limit' => 'integer',
        'ai_chat_limit' => 'integer',
        'features' => 'array',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get institutions with this package
     */
    public function institutions()
    {
        return $this->hasMany(Institution::class, 'subscription_package_id');
    }

    /**
     * Check if package is unlimited for problems
     */
    public function hasUnlimitedProblems(): bool
    {
        return $this->problem_limit === null;
    }

    /**
     * Check if package is unlimited for applicants
     */
    public function hasUnlimitedApplicants(): bool
    {
        return $this->applicant_limit === null;
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute(): string
    {
        if ($this->price == 0) {
            return 'Gratis';
        }

        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    /**
     * Get duration in readable format
     */
    public function getDurationLabelAttribute(): string
    {
        if ($this->duration_months === 1) {
            return '1 Bulan';
        }

        return $this->duration_months . ' Bulan';
    }

    /**
     * Scope for active packages only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for featured packages
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope ordered by sort_order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}
