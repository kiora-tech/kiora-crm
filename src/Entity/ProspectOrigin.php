<?php

namespace App\Entity;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum ProspectOrigin: string implements TranslatableInterface
{
    case PARTNER = 'Partenaire';
    case TRADE_SHOW = 'Salon professionnel';
    case LISTING = 'Listing';
    case WEBSITE = 'Site web';
    case REFERRAL = 'Référencement';
    case OTHER = 'Autre';

    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        return $translator->trans($this->value, locale:$locale);
    }
}