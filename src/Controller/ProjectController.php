<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\Interaction;
use App\Entity\Task;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/project')]
class ProjectController extends AbstractController
{
    #[Route('/', name: 'project_index', methods: ['GET'])]
    public function index(Request $request, ProjectRepository $projectRepository, PaginationService $paginationService): Response
    {
        $queryBuilder = $projectRepository->createQueryBuilder('p')
            ->leftJoin('p.client', 'c')
            ->leftJoin('p.manager', 'm')
            ->orderBy('p.createdAt', 'DESC');

        $pagination = $paginationService->paginate(
            $queryBuilder,
            $request
        );

        return $this->render('project/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/new', name: 'project_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $project = new Project();
        
        // Définir l'utilisateur courant comme gestionnaire de projet par défaut
        $project->setManager($this->getUser());
        
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($project);
            $entityManager->flush();

            $this->addFlash('success', 'project.created_successfully');
            return $this->redirectToRoute('project_index');
        }

        return $this->render('project/new.html.twig', [
            'project' => $project,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'project_show', methods: ['GET'])]
    public function show(Project $project, EntityManagerInterface $entityManager): Response
    {
        // Récupérer les tâches liées au projet
        $tasks = $entityManager->getRepository(Task::class)->findBy(
            ['project' => $project],
            ['dueDate' => 'ASC']
        );
        
        // Récupérer les interactions liées au projet
        $interactions = $entityManager->getRepository(Interaction::class)->findBy(
            ['project' => $project],
            ['dateTime' => 'DESC']
        );
        
        return $this->render('project/show.html.twig', [
            'project' => $project,
            'tasks' => $tasks,
            'interactions' => $interactions,
        ]);
    }

    #[Route('/{id}/edit', name: 'project_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Project $project, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $project->setUpdatedAt();
            $entityManager->flush();

            $this->addFlash('success', 'project.updated_successfully');
            return $this->redirectToRoute('project_show', ['id' => $project->getId()]);
        }

        return $this->render('project/edit.html.twig', [
            'project' => $project,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'project_delete', methods: ['POST'])]
    public function delete(Request $request, Project $project, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$project->getId(), $request->request->get('_token'))) {
            $entityManager->remove($project);
            $entityManager->flush();
            
            $this->addFlash('success', 'project.deleted_successfully');
        }

        return $this->redirectToRoute('project_index');
    }
    
    #[Route('/{id}/status/{status}', name: 'project_change_status', methods: ['GET'])]
    public function changeStatus(Project $project, string $status, EntityManagerInterface $entityManager): Response
    {
        $statusEnum = \App\Enum\ProjectStatus::from(strtoupper($status));
        $project->setStatus($statusEnum);
        $entityManager->flush();
        
        $this->addFlash('success', 'project.status_updated');
        
        return $this->redirectToRoute('project_show', ['id' => $project->getId()]);
    }
    
    #[Route('/filter/status/{status}', name: 'project_filter_status', methods: ['GET'])]
    public function filterByStatus(string $status, Request $request, ProjectRepository $projectRepository, PaginationService $paginationService): Response
    {
        $statusEnum = \App\Enum\ProjectStatus::from(strtoupper($status));
        
        $queryBuilder = $projectRepository->createQueryBuilder('p')
            ->where('p.status = :status')
            ->setParameter('status', $statusEnum)
            ->leftJoin('p.client', 'c')
            ->leftJoin('p.manager', 'm')
            ->orderBy('p.createdAt', 'DESC');

        $pagination = $paginationService->paginate(
            $queryBuilder,
            $request
        );

        return $this->render('project/index.html.twig', [
            'pagination' => $pagination,
            'current_filter' => 'status_' . strtolower($status),
        ]);
    }
    
    #[Route('/filter/client/{id}', name: 'project_filter_client', methods: ['GET'])]
    public function filterByClient(int $id, Request $request, ProjectRepository $projectRepository, PaginationService $paginationService): Response
    {
        $queryBuilder = $projectRepository->createQueryBuilder('p')
            ->where('p.client = :clientId')
            ->setParameter('clientId', $id)
            ->leftJoin('p.client', 'c')
            ->leftJoin('p.manager', 'm')
            ->orderBy('p.createdAt', 'DESC');

        $pagination = $paginationService->paginate(
            $queryBuilder,
            $request
        );

        return $this->render('project/index.html.twig', [
            'pagination' => $pagination,
            'current_filter' => 'client_' . $id,
        ]);
    }
}