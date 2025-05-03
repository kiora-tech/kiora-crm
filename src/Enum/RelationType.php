<?php

namespace App\Enum;

enum RelationType: string
{
    case CLIENT = 'CLIENT';
    case PROSPECT = 'PROSPECT';
    case CONTACT = 'CONTACT';
    case SUPPLIER = 'SUPPLIER';
    case PARTNER = 'PARTNER';
    case SUBSIDIARY = 'SUBSIDIARY';
    case PARENT_COMPANY = 'PARENT_COMPANY';
    case OTHER = 'OTHER';
}