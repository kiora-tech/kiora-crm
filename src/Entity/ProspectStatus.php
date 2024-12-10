<?php

namespace App\Entity;

enum ProspectStatus: string
{
    case WON = 'Gagné';
    case LOST = 'Perdu';
    case IN_PROGRESS = 'En cours';
    case ACQUISITION = 'Acquisition';
    case RENEWAL = 'Renouvellement';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}