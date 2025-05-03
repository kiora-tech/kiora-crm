<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/task')]
class TaskController extends AbstractController
{
    #[Route('/', name: 'task_index', methods: ['GET'])]
    public function index(Request $request, TaskRepository $taskRepository, PaginationService $paginationService): Response
    {
        $queryBuilder = $taskRepository->createQueryBuilder('t')
            ->leftJoin('t.project', 'p')
            ->leftJoin('t.assignee', 'u')
            ->orderBy('t.dueDate', 'ASC');

        $pagination = $paginationService->paginate(
            $queryBuilder,
            $request
        );

        return $this->render('task/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/new', name: 'task_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $task = new Task();
        
        // Assigner la tâche à l'utilisateur courant par défaut
        $task->setAssignee($this->getUser());
        
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($task);
            $entityManager->flush();

            $this->addFlash('success', 'task.created_successfully');
            return $this->redirectToRoute('task_index');
        }

        return $this->render('task/new.html.twig', [
            'task' => $task,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'task_show', methods: ['GET'])]
    public function show(Task $task): Response
    {
        return $this->render('task/show.html.twig', [
            'task' => $task,
        ]);
    }

    #[Route('/{id}/edit', name: 'task_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Task $task, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task->setUpdatedAt();
            $entityManager->flush();

            $this->addFlash('success', 'task.updated_successfully');
            return $this->redirectToRoute('task_show', ['id' => $task->getId()]);
        }

        return $this->render('task/edit.html.twig', [
            'task' => $task,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'task_delete', methods: ['POST'])]
    public function delete(Request $request, Task $task, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$task->getId(), $request->request->get('_token'))) {
            $entityManager->remove($task);
            $entityManager->flush();
            
            $this->addFlash('success', 'task.deleted_successfully');
        }

        return $this->redirectToRoute('task_index');
    }
    
    #[Route('/{id}/status/{status}', name: 'task_change_status', methods: ['GET'])]
    public function changeStatus(Task $task, string $status, EntityManagerInterface $entityManager): Response
    {
        $statusEnum = \App\Enum\TaskStatus::from(strtoupper($status));
        $task->setStatus($statusEnum);
        $entityManager->flush();
        
        $this->addFlash('success', 'task.status_updated');
        
        return $this->redirectToRoute('task_show', ['id' => $task->getId()]);
    }
}