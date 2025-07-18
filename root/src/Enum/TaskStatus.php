<?php

namespace App\Enum;

enum TaskStatus: string
{
    case Pending = 'pending';
    case InProgress = 'in_progress';
    case WaitingForClient = 'waiting_for_client';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
    case Closed = 'closed';

    public const VALUES = ['pending', 'in_progress', 'waiting_for_client', 'completed', 'cancelled', 'closed'];
}
