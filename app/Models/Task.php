<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'client_id',
        'contact_id',
        'title',
        'description',
        'status',
        'due_date',
        'priority',
        'user_id',
    ];

    protected $casts = [
        'due_date' => 'date',
        // 'status' => TaskStatus::class,
        // 'priority' => TaskPriority::class,
    ];

    // Constants
    public const STATUS_PENDING = 'pending';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_COMPLETED = 'completed';

    public const STATUSES = [
        self::STATUS_PENDING => 'Pending',
        self::STATUS_IN_PROGRESS => 'In Progress',
        self::STATUS_COMPLETED => 'Completed',
    ];

    public const PRIORITY_LOW = 'low';
    public const PRIORITY_MEDIUM = 'medium';
    public const PRIORITY_HIGH = 'high';

    public const PRIORITIES = [
        self::PRIORITY_LOW => 'Low',
        self::PRIORITY_MEDIUM => 'Medium',
        self::PRIORITY_HIGH => 'High',
    ];

    // Relationships
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class)->withDefault();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    // Scopes
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeInProgress(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_IN_PROGRESS);
    }

    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    public function scopeOverdue(Builder $query): Builder
    {
        return $query->where('due_date', '<', now())
                    ->where('status', '!=', self::STATUS_COMPLETED);
    }

    public function scopeDueSoon(Builder $query, int $days = 7): Builder
    {
        return $query->whereBetween('due_date', [now(), now()->addDays($days)])
                    ->where('status', '!=', self::STATUS_COMPLETED);
    }

    public function scopeByPriority(Builder $query, string $priority): Builder
    {
        return $query->where('priority', $priority);
    }

    // Accessors & Mutators
    public function getStatusAttribute($value)
    {
        if ($value === null) {
            return null;
        }

        try {
            return TaskStatus::from($value);
        } catch (\ValueError $e) {
            return TaskStatus::PENDING;
        }
    }

    public function setStatusAttribute($value): void
    {
        if ($value instanceof TaskStatus) {
            $this->attributes['status'] = $value->value;
        } else {
            $this->attributes['status'] = $value;
        }
    }

    public function getPriorityAttribute($value)
    {
        if ($value === null) {
            return null;
        }

        try {
            return TaskPriority::from($value);
        } catch (\ValueError $e) {
            return TaskPriority::MEDIUM;
        }
    }

    public function setPriorityAttribute($value): void
    {
        if ($value instanceof TaskPriority) {
            $this->attributes['priority'] = $value->value;
        } else {
            $this->attributes['priority'] = $value;
        }
    }

    public function getIsPendingAttribute(): bool
    {
        return $this->status?->value === self::STATUS_PENDING;
    }

    public function getIsInProgressAttribute(): bool
    {
        return $this->status?->value === self::STATUS_IN_PROGRESS;
    }

    public function getIsCompletedAttribute(): bool
    {
        return $this->status?->value === self::STATUS_COMPLETED;
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->due_date && $this->due_date->isPast() && !$this->isCompleted;
    }

    public function getDaysUntilDueAttribute(): ?int
    {
        return $this->due_date ? now()->diffInDays($this->due_date, false) : null;
    }

    public function getFormattedDueDateAttribute(): string
    {
        return $this->due_date ? $this->due_date->format('M j, Y') : 'No due date';
    }


    public function getRecentActivities(int $limit = 3): Collection
    {
        return $this->activities()
            ->orderBy('date', 'desc')
            ->limit($limit)
            ->get();
    }

    public function canBeEditedBy(User $user): bool
    {
        // Business rule: only assigned user or admin can edit
        return ($this->user_id && $this->user_id === $user->id) || $user->isAdmin();
    }
}