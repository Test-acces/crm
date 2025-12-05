<?php

namespace App\Models;

use App\Models\TaskStatus;
use App\Traits\HasTasks;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class Contact extends Model
{
    use HasFactory, HasTasks, SoftDeletes;

    protected $fillable = [
        'client_id',
        'name',
        'email',
        'phone',
        'position',
        'notes',
    ];

    // Relationships
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    // Scopes
    public function scopeByClient(Builder $query, int $clientId): Builder
    {
        return $query->where('client_id', $clientId);
    }

    public function scopeWithPosition(Builder $query): Builder
    {
        return $query->whereNotNull('position');
    }

    // Accessors & Mutators
    public function getFullNameAttribute(): string
    {
        return $this->name;
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->position
            ? "{$this->name} ({$this->position})"
            : $this->name;
    }

    public function getPrimaryContactInfoAttribute(): string
    {
        return $this->email ?: ($this->phone ?: 'No contact info');
    }

    // Business Logic Methods
    public function getRecentActivities(int $limit = 3): Collection
    {
        return $this->activities()
            ->orderBy('date', 'desc')
            ->limit($limit)
            ->get();
    }

    public function canBeDeleted(): bool
    {
        return $this->tasks()->doesntExist() && $this->activities()->doesntExist();
    }

    public function updatePosition(?string $position): bool
    {
        return $this->update(['position' => $position]);
    }
}