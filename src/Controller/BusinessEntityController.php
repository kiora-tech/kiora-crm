<?php

namespace App\Controller;

use App\Entity\BusinessEntity;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/business/entity', name: 'app_business_entity')]
final class BusinessEntityController extends CustomerInfoController
{
    protected function getEntityClass(): string
    {
        return BusinessEntity::class;
    }
}
