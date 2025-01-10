<?php

namespace App\Entity;

enum ProspectOrigin: string
{
    case PARTNER = 'Partenaire';
    case TRADE_SHOW = 'Salon professionnel';
    case LISTING = 'Listing';
    case WEBSITE = 'Site web';
    case REFERRAL = 'Référencement';
    case OTHER = 'Autre';
}