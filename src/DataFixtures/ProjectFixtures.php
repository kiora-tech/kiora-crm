<?php

namespace App\DataFixtures;

use App\Entity\PhysicalPerson;
use App\Entity\Project;
use App\Entity\User;
use App\Enum\ProjectStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProjectFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $projects = [
            [
                'title' => 'Développement Site Web E-commerce',
                'description' => 'Création d\'un site e-commerce complet avec catalogue de produits, système de paiement et administration.',
                'startDate' => new \DateTime('-2 months'),
                'endDate' => new \DateTime('+4 months'),
                'status' => ProjectStatus::IN_PROGRESS,
                'client' => 'physical_person_1',
                'manager' => 'test@test.com',
                'budget' => 25000,
                'reference' => 'WEB-2023-001',
                'tags' => ['e-commerce', 'web', 'symfony']
            ],
            [
                'title' => 'Refonte Application Mobile',
                'description' => 'Refonte complète de l\'application mobile existante avec nouvelles fonctionnalités et design amélioré.',
                'startDate' => new \DateTime('-1 month'),
                'endDate' => new \DateTime('+2 months'),
                'status' => ProjectStatus::IN_PROGRESS,
                'client' => 'physical_person_4',
                'manager' => 'test@test.com',
                'budget' => 15000,
                'reference' => 'MOB-2023-002',
                'tags' => ['mobile', 'flutter', 'ui/ux']
            ],
            [
                'title' => 'Audit Sécurité Informatique',
                'description' => 'Audit complet de la sécurité informatique de l\'entreprise avec rapport et recommandations.',
                'startDate' => new \DateTime('+1 week'),
                'endDate' => new \DateTime('+1 month'),
                'status' => ProjectStatus::PENDING,
                'client' => 'physical_person_5',
                'manager' => 'test@test.com',
                'budget' => 8000,
                'reference' => 'SEC-2023-003',
                'tags' => ['sécurité', 'audit', 'penetration-testing']
            ],
            [
                'title' => 'Formation Développement Agile',
                'description' => 'Formation des équipes aux méthodes de développement agile et mise en place d\'outils.',
                'startDate' => new \DateTime('-3 months'),
                'endDate' => new \DateTime('-1 month'),
                'status' => ProjectStatus::COMPLETED,
                'client' => 'physical_person_4',
                'manager' => 'test@test.com',
                'budget' => 5000,
                'reference' => 'FOR-2023-004',
                'tags' => ['formation', 'agile', 'scrum']
            ],
            [
                'title' => 'Système de Gestion Interne',
                'description' => 'Développement d\'un système de gestion interne pour les ressources humaines et la comptabilité.',
                'startDate' => new \DateTime('-1 month'),
                'endDate' => null,
                'status' => ProjectStatus::ON_HOLD,
                'client' => 'physical_person_3',
                'manager' => 'test@test.com',
                'budget' => 20000,
                'reference' => 'ERP-2023-005',
                'tags' => ['erp', 'gestion', 'dashboard']
            ],
        ];

        foreach ($projects as $index => $projectData) {
            $project = new Project();
            $project->setTitle($projectData['title']);
            $project->setDescription($projectData['description']);
            $project->setStartDate($projectData['startDate']);
            $project->setEndDate($projectData['endDate']);
            $project->setStatus($projectData['status']);
            
            // Get the client reference (Person entity)
            $clientRef = $projectData['client'];
            $project->setClient($this->getReference($clientRef, PhysicalPerson::class));
            
            // Get the manager reference (User entity)
            $project->setManager($this->getUserByEmail($manager, $projectData['manager']));
            
            $project->setBudget($projectData['budget']);
            $project->setReference($projectData['reference']);
            
            // Set tags
            foreach ($projectData['tags'] as $tag) {
                $project->addTag($tag);
            }
            
            $manager->persist($project);
            $this->addReference('project_' . ($index + 1), $project);
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
            LegalPersonFixtures::class,
            PhysicalPersonFixtures::class,
            UserFixtures::class,
        ];
    }
}