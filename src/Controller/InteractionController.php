<?php

namespace App\Controller;

use App\Entity\Interaction;
use App\Entity\LegalPerson;
use App\Entity\PhysicalPerson;
use App\Entity\Project;
use App\Entity\Task;
use App\Form\InteractionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/interaction')]
class InteractionController extends AbstractController
{
    /**
     * Ajouter une interaction à une personne physique
     */
    #[Route('/physical-person/{id}/new', name: 'interaction_new_physical_person', methods: ['GET', 'POST'])]
    public function newForPhysicalPerson(
        Request $request, 
        PhysicalPerson $person, 
        EntityManagerInterface $entityManager
    ): Response {
        $interaction = new Interaction();
        $interaction->setContact($person);
        $interaction->setUser($this->getUser());
        
        $form = $this->createForm(InteractionType::class, $interaction, [
            'hide_contact' => true,
        ]);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($interaction);
            $entityManager->flush();

            $this->addFlash('success', 'interaction.created_successfully');
            
            return $this->redirectToRoute('physical_person_show', ['id' => $person->getId()]);
        }

        return $this->render('interaction/new.html.twig', [
            'form' => $form,
            'back_route' => 'physical_person_show',
            'back_route_params' => ['id' => $person->getId()],
            'entity' => $person,
            'entity_type' => 'physical_person',
        ]);
    }
    
    /**
     * Ajouter une interaction à une personne morale
     */
    #[Route('/legal-person/{id}/new', name: 'interaction_new_legal_person', methods: ['GET', 'POST'])]
    public function newForLegalPerson(
        Request $request, 
        LegalPerson $person, 
        EntityManagerInterface $entityManager
    ): Response {
        $interaction = new Interaction();
        $interaction->setContact($person);
        $interaction->setUser($this->getUser());
        
        $form = $this->createForm(InteractionType::class, $interaction, [
            'hide_contact' => true,
        ]);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($interaction);
            $entityManager->flush();

            $this->addFlash('success', 'interaction.created_successfully');
            
            return $this->redirectToRoute('legal_person_show', ['id' => $person->getId()]);
        }

        return $this->render('interaction/new.html.twig', [
            'form' => $form,
            'back_route' => 'legal_person_show',
            'back_route_params' => ['id' => $person->getId()],
            'entity' => $person,
            'entity_type' => 'legal_person',
        ]);
    }
    
    /**
     * Ajouter une interaction à un projet
     */
    #[Route('/project/{id}/new', name: 'interaction_new_project', methods: ['GET', 'POST'])]
    public function newForProject(
        Request $request, 
        Project $project, 
        EntityManagerInterface $entityManager
    ): Response {
        $interaction = new Interaction();
        $interaction->setProject($project);
        $interaction->setUser($this->getUser());
        
        $form = $this->createForm(InteractionType::class, $interaction, [
            'hide_project' => true,
        ]);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($interaction);
            $entityManager->flush();

            $this->addFlash('success', 'interaction.created_successfully');
            
            return $this->redirectToRoute('project_show', ['id' => $project->getId()]);
        }

        return $this->render('interaction/new.html.twig', [
            'form' => $form,
            'back_route' => 'project_show',
            'back_route_params' => ['id' => $project->getId()],
            'entity' => $project,
            'entity_type' => 'project',
        ]);
    }
    
    /**
     * Ajouter une interaction à une tâche
     */
    #[Route('/task/{id}/new', name: 'interaction_new_task', methods: ['GET', 'POST'])]
    public function newForTask(
        Request $request, 
        Task $task, 
        EntityManagerInterface $entityManager
    ): Response {
        $interaction = new Interaction();
        $interaction->setTask($task);
        $interaction->setUser($this->getUser());
        
        $form = $this->createForm(InteractionType::class, $interaction, [
            'hide_task' => true,
        ]);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($interaction);
            $entityManager->flush();

            $this->addFlash('success', 'interaction.created_successfully');
            
            return $this->redirectToRoute('task_show', ['id' => $task->getId()]);
        }

        return $this->render('interaction/new.html.twig', [
            'form' => $form,
            'back_route' => 'task_show',
            'back_route_params' => ['id' => $task->getId()],
            'entity' => $task,
            'entity_type' => 'task',
        ]);
    }
    
    /**
     * Voir les détails d'une interaction
     */
    #[Route('/{id}', name: 'interaction_show', methods: ['GET'])]
    public function show(Interaction $interaction): Response
    {
        return $this->render('interaction/show.html.twig', [
            'interaction' => $interaction,
        ]);
    }
    
    /**
     * Modifier une interaction
     */
    #[Route('/{id}/edit', name: 'interaction_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request, 
        Interaction $interaction, 
        EntityManagerInterface $entityManager
    ): Response {
        $form = $this->createForm(InteractionType::class, $interaction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'interaction.updated_successfully');
            
            return $this->redirectToRoute('interaction_show', ['id' => $interaction->getId()]);
        }

        return $this->render('interaction/edit.html.twig', [
            'form' => $form,
            'interaction' => $interaction,
        ]);
    }
    
    /**
     * Supprimer une interaction
     */
    #[Route('/{id}', name: 'interaction_delete', methods: ['POST'])]
    public function delete(
        Request $request, 
        Interaction $interaction, 
        EntityManagerInterface $entityManager
    ): Response {
        if ($this->isCsrfTokenValid('delete'.$interaction->getId(), $request->request->get('_token'))) {
            $entityManager->remove($interaction);
            $entityManager->flush();
            
            $this->addFlash('success', 'interaction.deleted_successfully');
        }

        // Rediriger vers la page appropriée en fonction de l'entité associée
        if ($interaction->getContact()) {
            if ($interaction->getContact() instanceof PhysicalPerson) {
                return $this->redirectToRoute('physical_person_show', ['id' => $interaction->getContact()->getId()]);
            } else {
                return $this->redirectToRoute('legal_person_show', ['id' => $interaction->getContact()->getId()]);
            }
        } elseif ($interaction->getProject()) {
            return $this->redirectToRoute('project_show', ['id' => $interaction->getProject()->getId()]);
        } elseif ($interaction->getTask()) {
            return $this->redirectToRoute('task_show', ['id' => $interaction->getTask()->getId()]);
        }
        
        return $this->redirectToRoute('homepage');
    }
}