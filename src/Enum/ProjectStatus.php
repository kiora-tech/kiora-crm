<?php

namespace App\Enum;

enum ProjectStatus: string
{
    case PENDING = 'PENDING';
    case IN_PROGRESS = 'IN_PROGRESS';
    case COMPLETED = 'COMPLETED';
    case CANCELLED = 'CANCELLED';
    case ON_HOLD = 'ON_HOLD';
}
