<?php

namespace App\Controller;

use App\Entity\Contact;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;

#[Route('/contact', name: 'app_contact')]
final class ContactController extends CustomerInfoController
{
   public function getEntityClass(): string
   {
       return Contact::class;
   }

   #[Route('/{id}', name: '_show', methods: ['GET'])]
    public function show(Contact $contact): Response
    {
        return $this->render('contact/show.html.twig', [
            'entity' => $contact,
        ]);
    }
}
