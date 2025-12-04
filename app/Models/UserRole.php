<?php

namespace App\Models;

enum UserRole: string
{
    case ADMIN = 'admin';
    case MANAGER = 'manager';
    case COMMERCIAL = 'commercial';
    case VIEWER = 'viewer';

    public function label(): string
    {
        return match($this) {
            self::ADMIN => 'Admin',
            self::MANAGER => 'Manager',
            self::COMMERCIAL => 'Commercial',
            self::VIEWER => 'Viewer',
        };
    }

    public function canSeeAllClients(): bool
    {
        return in_array($this, [self::ADMIN, self::MANAGER]);
    }

    public function canManageUsers(): bool
    {
        return $this === self::ADMIN;
    }

    public function canAccessSettings(): bool
    {
        return $this === self::ADMIN;
    }
}