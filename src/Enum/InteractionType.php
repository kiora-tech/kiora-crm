<?php

namespace App\Enum;

enum InteractionType: string
{
    case CALL = 'CALL';
    case EMAIL = 'EMAIL';
    case MEETING = 'MEETING';
    case NOTE = 'NOTE';
    case SMS = 'SMS';
    case OTHER = 'OTHER';
}
