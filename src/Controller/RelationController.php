<?php

namespace App\Controller;

use App\Entity\LegalPerson;
use App\Entity\PhysicalPerson;
use App\Entity\Relation;
use App\Form\RelationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/relation')]
class RelationController extends AbstractController
{
    /**
     * Ajouter une relation à une personne physique
     */
    #[Route('/physical-person/{id}/new', name: 'relation_new_physical_person', methods: ['GET', 'POST'])]
    public function newForPhysicalPerson(
        Request $request, 
        PhysicalPerson $person, 
        EntityManagerInterface $entityManager
    ): Response {
        $relation = new Relation();
        $relation->setSourcePerson($person);
        
        $form = $this->createForm(RelationType::class, $relation, [
            'source_person' => $person,
        ]);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($relation);
            $entityManager->flush();

            $this->addFlash('success', 'relation.created_successfully');
            
            return $this->redirectToRoute('physical_person_show', ['id' => $person->getId()]);
        }

        return $this->render('relation/new.html.twig', [
            'form' => $form,
            'back_route' => 'physical_person_show',
            'back_route_params' => ['id' => $person->getId()],
            'entity' => $person,
            'entity_type' => 'physical_person',
        ]);
    }
    
    /**
     * Ajouter une relation à une personne morale
     */
    #[Route('/legal-person/{id}/new', name: 'relation_new_legal_person', methods: ['GET', 'POST'])]
    public function newForLegalPerson(
        Request $request, 
        LegalPerson $person, 
        EntityManagerInterface $entityManager
    ): Response {
        $relation = new Relation();
        $relation->setSourcePerson($person);
        
        $form = $this->createForm(RelationType::class, $relation, [
            'source_person' => $person,
        ]);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($relation);
            $entityManager->flush();

            $this->addFlash('success', 'relation.created_successfully');
            
            return $this->redirectToRoute('legal_person_show', ['id' => $person->getId()]);
        }

        return $this->render('relation/new.html.twig', [
            'form' => $form,
            'back_route' => 'legal_person_show',
            'back_route_params' => ['id' => $person->getId()],
            'entity' => $person,
            'entity_type' => 'legal_person',
        ]);
    }
    
    /**
     * Modifier une relation
     */
    #[Route('/{id}/edit', name: 'relation_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request, 
        Relation $relation, 
        EntityManagerInterface $entityManager
    ): Response {
        $form = $this->createForm(RelationType::class, $relation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $relation->setUpdatedAt();
            $entityManager->flush();

            $this->addFlash('success', 'relation.updated_successfully');
            
            // Rediriger vers la page de détails de la source
            if ($relation->getSourcePerson() instanceof PhysicalPerson) {
                return $this->redirectToRoute('physical_person_show', ['id' => $relation->getSourcePerson()->getId()]);
            } else {
                return $this->redirectToRoute('legal_person_show', ['id' => $relation->getSourcePerson()->getId()]);
            }
        }

        return $this->render('relation/edit.html.twig', [
            'form' => $form,
            'relation' => $relation,
        ]);
    }
    
    /**
     * Supprimer une relation
     */
    #[Route('/{id}', name: 'relation_delete', methods: ['POST'])]
    public function delete(
        Request $request, 
        Relation $relation, 
        EntityManagerInterface $entityManager
    ): Response {
        $sourcePerson = $relation->getSourcePerson();
        
        if ($this->isCsrfTokenValid('delete'.$relation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($relation);
            $entityManager->flush();
            
            $this->addFlash('success', 'relation.deleted_successfully');
        }

        // Rediriger vers la page de détails de la source
        if ($sourcePerson instanceof PhysicalPerson) {
            return $this->redirectToRoute('physical_person_show', ['id' => $sourcePerson->getId()]);
        } else {
            return $this->redirectToRoute('legal_person_show', ['id' => $sourcePerson->getId()]);
        }
    }
}