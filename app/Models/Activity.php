<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'client_id',
        'contact_id',
        'task_id',
        'user_id',
        'type',
        'description',
        'date',
    ];

    protected $casts = [
        'date' => 'datetime',
        // 'type' => ActivityType::class,
    ];

    // Constants
    public const TYPE_CALL = 'call';
    public const TYPE_EMAIL = 'email';
    public const TYPE_MEETING = 'meeting';
    public const TYPE_NOTE = 'note';
    public const TYPE_TASK_CREATED = 'task_created';
    public const TYPE_TASK_UPDATED = 'task_updated';

    public const TYPES = [
        self::TYPE_CALL => 'Call',
        self::TYPE_EMAIL => 'Email',
        self::TYPE_MEETING => 'Meeting',
        self::TYPE_NOTE => 'Note',
        self::TYPE_TASK_CREATED => 'Task Created',
        self::TYPE_TASK_UPDATED => 'Task Updated',
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

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class)->withDefault();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    // Scopes
    public function scopeByClient(Builder $query, int $clientId): Builder
    {
        return $query->where('client_id', $clientId);
    }

    public function scopeByType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    public function scopeRecent(Builder $query, int $days = 7): Builder
    {
        return $query->where('date', '>=', now()->subDays($days));
    }

    public function scopeByUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    // Accessors & Mutators
    public function getTypeAttribute($value)
    {
        if ($value === null) {
            return null;
        }

        try {
            return ActivityType::from($value);
        } catch (\ValueError $e) {
            return ActivityType::NOTE;
        }
    }

    public function setTypeAttribute($value): void
    {
        if ($value instanceof ActivityType) {
            $this->attributes['type'] = $value->value;
        } else {
            $this->attributes['type'] = $value;
        }
    }

    public function getFormattedDateAttribute(): string
    {
        return $this->date->format('M j, Y g:i A');
    }

    public function getTimeAgoAttribute(): string
    {
        return $this->date->diffForHumans();
    }

    public function getRelatedEntityNameAttribute(): string
    {
        if ($this->task_id) {
            return $this->relationLoaded('task') ? "Task: {$this->task->title}" : "Task: {$this->task_id}";
        }

        if ($this->contact_id) {
            return $this->relationLoaded('contact') ? "Contact: {$this->contact->name}" : "Contact: {$this->contact_id}";
        }

        return $this->relationLoaded('client') ? "Client: {$this->client->name}" : "Client: {$this->client_id}";
    }

    // Business Logic Methods
    public static function log(array $attributes): self
    {
        $user = auth()->user();
        $clientId = $attributes['client_id'] ?? null;

        // Check if user can log activities for this client
        if ($user && !$user->can('logForClient', [self::class, $clientId])) {
            throw new \Illuminate\Auth\Access\AuthorizationException('You are not authorized to log activities for this client.');
        }

        return self::create($attributes);
    }

    public function isRecent(): bool
    {
        return $this->date->isAfter(now()->subDays(7));
    }

    public function getRelatedUrl(): ?string
    {
        if ($this->task_id) {
            return "/admin/tasks/{$this->task_id}/edit";
        }

        if ($this->contact_id) {
            return "/admin/contacts/{$this->contact_id}/edit";
        }

        if ($this->client_id) {
            return "/admin/clients/{$this->client_id}/edit";
        }

        return null;
    }
}