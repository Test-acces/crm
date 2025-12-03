<?php

namespace App\Models;

enum ActivityType: string
{
    case CALL = 'call';
    case EMAIL = 'email';
    case MEETING = 'meeting';
    case NOTE = 'note';
    case TASK_CREATED = 'task_created';
    case TASK_UPDATED = 'task_updated';

    public function label(): string
    {
        return match($this) {
            self::CALL => 'Call',
            self::EMAIL => 'Email',
            self::MEETING => 'Meeting',
            self::NOTE => 'Note',
            self::TASK_CREATED => 'Task Created',
            self::TASK_UPDATED => 'Task Updated',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::CALL => 'blue',
            self::EMAIL => 'green',
            self::MEETING => 'purple',
            self::NOTE => 'gray',
            self::TASK_CREATED => 'success',
            self::TASK_UPDATED => 'warning',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::CALL => 'heroicon-o-phone',
            self::EMAIL => 'heroicon-o-envelope',
            self::MEETING => 'heroicon-o-users',
            self::NOTE => 'heroicon-o-document-text',
            self::TASK_CREATED => 'heroicon-o-plus-circle',
            self::TASK_UPDATED => 'heroicon-o-pencil-square',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (ActivityType $type) => [
                $type->value => $type->label()
            ])
            ->toArray();
    }
}