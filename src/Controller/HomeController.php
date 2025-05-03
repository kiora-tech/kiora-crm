<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\Project;
use App\Entity\PhysicalPerson;
use App\Entity\LegalPerson;
use App\Entity\Interaction;
use App\Enum\ProjectStatus;
use App\Enum\TaskStatus;
use App\Enum\TaskPriority;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Récupérer les statistiques des tâches
        $taskRepository = $entityManager->getRepository(Task::class);
        $tasksTotal = $taskRepository->count([]);
        $tasksTodo = $taskRepository->count(['status' => TaskStatus::TODO]);
        $tasksInProgress = $taskRepository->count(['status' => TaskStatus::IN_PROGRESS]);
        $tasksDone = $taskRepository->count(['status' => TaskStatus::DONE]);
        $tasksUrgent = $taskRepository->count(['priority' => TaskPriority::URGENT]);
        $tasksLate = count($taskRepository->findOverdueTasks());
        $tasksUpcoming = count($taskRepository->findUpcomingTasks(7));
        
        // Récupérer les tâches récentes assignées à l'utilisateur courant
        $userTasks = $taskRepository->findBy(
            ['assignee' => $this->getUser()],
            ['dueDate' => 'ASC'],
            10
        );
        
        // Récupérer les statistiques des projets
        $projectRepository = $entityManager->getRepository(Project::class);
        $projectsTotal = $projectRepository->count([]);
        $projectsInProgress = $projectRepository->count(['status' => ProjectStatus::IN_PROGRESS]);
        $projectsPending = $projectRepository->count(['status' => ProjectStatus::PENDING]);
        $projectsCompleted = $projectRepository->count(['status' => ProjectStatus::COMPLETED]);
        
        // Récupérer les projets récents
        $recentProjects = $projectRepository->findBy(
            [],
            ['createdAt' => 'DESC'],
            5
        );
        
        // Récupérer les statistiques des clients
        $physicalPersonRepository = $entityManager->getRepository(PhysicalPerson::class);
        $legalPersonRepository = $entityManager->getRepository(LegalPerson::class);
        $physicalPersonsCount = $physicalPersonRepository->count([]);
        $legalPersonsCount = $legalPersonRepository->count([]);
        $clientsTotal = $physicalPersonsCount + $legalPersonsCount;
        
        // Récupérer les dernières interactions
        $interactionRepository = $entityManager->getRepository(Interaction::class);
        $recentInteractions = $interactionRepository->findBy(
            [],
            ['dateTime' => 'DESC'],
            5
        );
        
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            // Statistiques des tâches
            'tasks_total' => $tasksTotal,
            'tasks_todo' => $tasksTodo,
            'tasks_in_progress' => $tasksInProgress,
            'tasks_done' => $tasksDone,
            'tasks_urgent' => $tasksUrgent,
            'tasks_late' => $tasksLate,
            'tasks_upcoming' => $tasksUpcoming,
            'user_tasks' => $userTasks,
            // Statistiques des projets
            'projects_total' => $projectsTotal,
            'projects_in_progress' => $projectsInProgress,
            'projects_pending' => $projectsPending,
            'projects_completed' => $projectsCompleted,
            'recent_projects' => $recentProjects,
            // Statistiques des clients
            'clients_total' => $clientsTotal,
            'physical_persons_count' => $physicalPersonsCount,
            'legal_persons_count' => $legalPersonsCount,
            // Récentes interactions
            'recent_interactions' => $recentInteractions,
        ]);
    }
}
