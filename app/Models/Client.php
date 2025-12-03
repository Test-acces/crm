<?php

namespace App\Models;

use App\Events\ClientCreated;
use App\Traits\CanBeDeleted;
use App\Traits\HasStatus;
use App\Traits\HasTasks;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Client extends Model
{
    use HasFactory, HasStatus, CanBeDeleted, HasTasks;

    protected $dispatchesEvents = [
        'created' => ClientCreated::class,
    ];

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'status',
        'notes',
    ];

    // Temporarily removed enum cast to avoid instantiation issues
    // protected $casts = [
    //     'status' => ClientStatus::class,
    // ];

    // Relationships
    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    // Accessors & Mutators
    public function getFullAddressAttribute(): string
    {
        return $this->address ?? 'No address provided';
    }

    /**
     * Get contacts count with eager loaded contacts
     */
    public function getContactsCount(): int
    {
        return $this->contacts->count();
    }

    /**
     * Get recent activities
     */
    public function getRecentActivities(int $limit = 5): Collection
    {
        return $this->activities()
            ->orderBy('date', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get relationships that block deletion
     */
    protected function getDeletionBlockingRelationships(): array
    {
        return ['contacts', 'tasks'];
    }

    /**
     * Get the status enum class for this model
     */
    protected function getStatusEnumClass(): string
    {
        return ClientStatus::class;
    }
}