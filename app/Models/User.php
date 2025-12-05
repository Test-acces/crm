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
        'role',
    ];

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
            'password' => 'hashed',
            // 'role' => UserRole::class,
        ];
    }

    /**
     * Get the role attribute as enum
     */
    public function getRoleAttribute($value)
    {
        if ($value === null) {
            return null;
        }

        try {
            return UserRole::from($value);
        } catch (\ValueError $e) {
            return UserRole::VIEWER;
        }
    }

    /**
     * Set the role attribute from enum
     */
    public function setRoleAttribute($value): void
    {
        if ($value instanceof UserRole) {
            $this->attributes['role'] = $value->value;
        } else {
            $this->attributes['role'] = $value;
        }
    }

    /**
     * Check if user has a specific role.
     */
    public function hasRole(string $role): bool
    {
        return $this->role?->value === $role;
    }

    /**
     * Check if user has admin role.
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Check if user can see all clients.
     */
    public function canSeeAllClients(): bool
    {
        return $this->role?->canSeeAllClients() ?? false;
    }

    /**
     * Check if user can manage users.
     */
    public function canManageUsers(): bool
    {
        return $this->role?->canManageUsers() ?? false;
    }

    /**
     * Check if user can access settings.
     */
    public function canAccessSettings(): bool
    {
        return $this->role?->canAccessSettings() ?? false;
    }
}
