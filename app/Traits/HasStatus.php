<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasStatus
{
    /**
     * Get the status attribute as enum
     */
    public function getStatusAttribute($value)
    {
        $enumClass = $this->getStatusEnumClass();
        return $enumClass::from($value);
    }

    /**
     * Set the status attribute from enum
     */
    public function setStatusAttribute($value): void
    {
        if ($value instanceof \UnitEnum) {
            $this->attributes['status'] = $value->value;
        } else {
            $this->attributes['status'] = $value;
        }
    }

    /**
     * Scope for active records
     */
    public function scopeActive(Builder $query): Builder
    {
        $enumClass = $this->getStatusEnumClass();
        return $query->where('status', $enumClass::ACTIVE->value);
    }

    /**
     * Scope for inactive records
     */
    public function scopeInactive(Builder $query): Builder
    {
        $enumClass = $this->getStatusEnumClass();
        return $query->where('status', $enumClass::INACTIVE->value);
    }

    /**
     * Check if record is active
     */
    public function getIsActiveAttribute(): bool
    {
        $enumClass = $this->getStatusEnumClass();
        return $this->status->value === $enumClass::ACTIVE->value;
    }

    /**
     * Get the status enum class for this model
     */
    abstract protected function getStatusEnumClass(): string;
}