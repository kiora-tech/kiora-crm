<?php

namespace App\Controller;

use App\Entity\Contact;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/contact', name: 'app_contact')]
final class ContactController extends CustomerInfoController
{
   public function getEntityClass(): string
   {
       return Contact::class;
   }
}
