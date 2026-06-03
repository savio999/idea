<?php

namespace App;

enum IdeaStatus: string
{
    case Pending = 'pending';
    case Completed = 'completed';
    case InProgress = 'in_progress';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Completed => 'Completed',
            self::InProgress => 'In Progress',
        };
    }
}
