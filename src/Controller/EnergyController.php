<?php

namespace App\Controller;

use App\Entity\Energy;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/energy', name: 'app_energy')]
final class EnergyController extends CustomerInfoController
{
   public function getEntityClass(): string
   {
       return Energy::class;
   }
}
