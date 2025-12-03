<?php

namespace App\Models;

enum TaskPriority: string
{
    case LOW = 'low';
    case MEDIUM = 'medium';
    case HIGH = 'high';

    public function label(): string
    {
        return match($this) {
            self::LOW => 'Low',
            self::MEDIUM => 'Medium',
            self::HIGH => 'High',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::LOW => 'success',
            self::MEDIUM => 'warning',
            self::HIGH => 'danger',
        };
    }

    public function weight(): int
    {
        return match($this) {
            self::LOW => 1,
            self::MEDIUM => 2,
            self::HIGH => 3,
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (TaskPriority $priority) => [
                $priority->value => $priority->label()
            ])
            ->toArray();
    }

    public static function ordered(): array
    {
        return collect(self::cases())
            ->sortBy(fn (TaskPriority $priority) => $priority->weight())
            ->mapWithKeys(fn (TaskPriority $priority) => [
                $priority->value => $priority->label()
            ])
            ->toArray();
    }
}