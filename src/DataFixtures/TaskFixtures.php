<?php

namespace App\DataFixtures;

use App\Entity\Project;
use App\Entity\Task;
use App\Enum\TaskPriority;
use App\Enum\TaskStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TaskFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $tasks = [
            // Tasks for Project 1: Website Development
            [
                'title' => 'Conception UI/UX',
                'description' => 'Conception de l\'interface utilisateur et de l\'expérience utilisateur pour le site e-commerce.',
                'dueDate' => new \DateTime('-1 month'),
                'status' => TaskStatus::DONE,
                'priority' => TaskPriority::HIGH,
                'project' => 'project_1',
                'assignee' => 'test@test.com',
                'startDate' => new \DateTime('-2 months'),
                'completedAt' => new \DateTime('-1 month'),
                'estimatedHours' => 40,
                'actualHours' => 45,
                'tags' => ['design', 'ui', 'ux']
            ],
            [
                'title' => 'Développement Frontend',
                'description' => 'Développement des composants frontend pour le site e-commerce avec intégration des maquettes.',
                'dueDate' => new \DateTime('+1 week'),
                'status' => TaskStatus::IN_PROGRESS,
                'priority' => TaskPriority::HIGH,
                'project' => 'project_1',
                'assignee' => 'test@test.com',
                'startDate' => new \DateTime('-3 weeks'),
                'completedAt' => null,
                'estimatedHours' => 80,
                'actualHours' => 60,
                'tags' => ['frontend', 'react', 'css']
            ],
            [
                'title' => 'Développement Backend',
                'description' => 'Développement des API et services backend pour le site e-commerce.',
                'dueDate' => new \DateTime('+2 weeks'),
                'status' => TaskStatus::IN_PROGRESS,
                'priority' => TaskPriority::HIGH,
                'project' => 'project_1',
                'assignee' => 'test@test.com',
                'startDate' => new \DateTime('-2 weeks'),
                'completedAt' => null,
                'estimatedHours' => 100,
                'actualHours' => 40,
                'tags' => ['backend', 'api', 'symfony']
            ],
            [
                'title' => 'Intégration Système de Paiement',
                'description' => 'Intégration du système de paiement Stripe et PayPal pour le site e-commerce.',
                'dueDate' => new \DateTime('+3 weeks'),
                'status' => TaskStatus::TODO,
                'priority' => TaskPriority::MEDIUM,
                'project' => 'project_1',
                'assignee' => 'test@test.com',
                'startDate' => null,
                'completedAt' => null,
                'estimatedHours' => 30,
                'actualHours' => 0,
                'tags' => ['payment', 'stripe', 'paypal']
            ],
            [
                'title' => 'Tests et QA',
                'description' => 'Tests fonctionnels et qualité du site e-commerce avant mise en production.',
                'dueDate' => new \DateTime('+1 month'),
                'status' => TaskStatus::TODO,
                'priority' => TaskPriority::MEDIUM,
                'project' => 'project_1',
                'assignee' => 'test@test.com',
                'startDate' => null,
                'completedAt' => null,
                'estimatedHours' => 40,
                'actualHours' => 0,
                'tags' => ['testing', 'qa', 'cypress']
            ],
            
            // Tasks for Project 2: Mobile App Redesign
            [
                'title' => 'Analyse des Besoins',
                'description' => 'Analyse des besoins et définition des fonctionnalités pour la refonte de l\'application mobile.',
                'dueDate' => new \DateTime('-2 weeks'),
                'status' => TaskStatus::DONE,
                'priority' => TaskPriority::HIGH,
                'project' => 'project_2',
                'assignee' => 'test@test.com',
                'startDate' => new \DateTime('-1 month'),
                'completedAt' => new \DateTime('-2 weeks'),
                'estimatedHours' => 20,
                'actualHours' => 18,
                'tags' => ['analyse', 'specifications']
            ],
            [
                'title' => 'Design UI Mobile',
                'description' => 'Création des maquettes et du design pour la nouvelle interface utilisateur mobile.',
                'dueDate' => new \DateTime('-1 week'),
                'status' => TaskStatus::DONE,
                'priority' => TaskPriority::HIGH,
                'project' => 'project_2',
                'assignee' => 'test@test.com',
                'startDate' => new \DateTime('-3 weeks'),
                'completedAt' => new \DateTime('-1 week'),
                'estimatedHours' => 30,
                'actualHours' => 32,
                'tags' => ['design', 'figma', 'mobile']
            ],
            [
                'title' => 'Développement Frontend Mobile',
                'description' => 'Développement des écrans et composants frontend pour l\'application mobile.',
                'dueDate' => new \DateTime('+2 weeks'),
                'status' => TaskStatus::IN_PROGRESS,
                'priority' => TaskPriority::HIGH,
                'project' => 'project_2',
                'assignee' => 'test@test.com',
                'startDate' => new \DateTime('-1 week'),
                'completedAt' => null,
                'estimatedHours' => 60,
                'actualHours' => 20,
                'tags' => ['flutter', 'mobile', 'ui']
            ],
            
            // Tasks for Project 3: Security Audit
            [
                'title' => 'Planification de l\'Audit',
                'description' => 'Planification et définition du périmètre de l\'audit de sécurité.',
                'dueDate' => new \DateTime('+1 week'),
                'status' => TaskStatus::TODO,
                'priority' => TaskPriority::HIGH,
                'project' => 'project_3',
                'assignee' => 'test@test.com',
                'startDate' => null,
                'completedAt' => null,
                'estimatedHours' => 10,
                'actualHours' => 0,
                'tags' => ['planification', 'sécurité']
            ],
            
            // Tasks for Project 4: Agile Training
            [
                'title' => 'Préparation du Matériel de Formation',
                'description' => 'Préparation des supports et matériels pour la formation aux méthodes agiles.',
                'dueDate' => new \DateTime('-3 months'),
                'status' => TaskStatus::DONE,
                'priority' => TaskPriority::MEDIUM,
                'project' => 'project_4',
                'assignee' => 'test@test.com',
                'startDate' => new \DateTime('-3 months'),
                'completedAt' => new \DateTime('-3 months'),
                'estimatedHours' => 20,
                'actualHours' => 15,
                'tags' => ['formation', 'supports']
            ],
            
            // Tasks for Project 5: Internal Management System
            [
                'title' => 'Analyse des Processus RH',
                'description' => 'Analyse des processus RH existants pour le système de gestion interne.',
                'dueDate' => new \DateTime('-3 weeks'),
                'status' => TaskStatus::DONE,
                'priority' => TaskPriority::MEDIUM,
                'project' => 'project_5',
                'assignee' => 'test@test.com',
                'startDate' => new \DateTime('-4 weeks'),
                'completedAt' => new \DateTime('-3 weeks'),
                'estimatedHours' => 25,
                'actualHours' => 20,
                'tags' => ['analyse', 'rh', 'processus']
            ],
            [
                'title' => 'Conception Base de Données',
                'description' => 'Conception du schéma de base de données pour le système de gestion interne.',
                'dueDate' => new \DateTime('-2 weeks'),
                'status' => TaskStatus::BLOCKED,
                'priority' => TaskPriority::HIGH,
                'project' => 'project_5',
                'assignee' => 'test@test.com',
                'startDate' => new \DateTime('-3 weeks'),
                'completedAt' => null,
                'estimatedHours' => 30,
                'actualHours' => 20,
                'tags' => ['database', 'schema', 'erp']
            ],
        ];

        foreach ($tasks as $taskData) {
            $task = new Task();
            $task->setTitle($taskData['title']);
            $task->setDescription($taskData['description']);
            $task->setDueDate($taskData['dueDate']);
            $task->setStatus($taskData['status']);
            $task->setPriority($taskData['priority']);
            
            // Set project reference
            $task->setProject($this->getReference($taskData['project'], Project::class));
            
            // Set assignee (User)
            $task->setAssignee($this->getUserByEmail($manager, $taskData['assignee']));
            
            // Set dates
            if ($taskData['startDate']) {
                $task->setStartDate($taskData['startDate']);
            }
            if ($taskData['completedAt']) {
                $task->setCompletedAt($taskData['completedAt']);
            }
            
            // Set hours
            $task->setEstimatedHours($taskData['estimatedHours']);
            $task->setActualHours($taskData['actualHours']);
            
            // Set tags
            if (isset($taskData['tags'])) {
                foreach ($taskData['tags'] as $tag) {
                    $task->addTag($tag);
                }
            }
            
            $manager->persist($task);
        }
        
        $manager->flush();
    }

    private function getUserByEmail(ObjectManager $manager, string $email)
    {
        return $manager->getRepository('App\Entity\User')->findOneBy(['email' => $email]);
    }

    public function getDependencies(): array
    {
        return [
            ProjectFixtures::class,
            UserFixtures::class,
        ];
    }
}