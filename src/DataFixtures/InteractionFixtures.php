<?php

namespace App\DataFixtures;

use App\Entity\Interaction;
use App\Entity\Person;
use App\Entity\PhysicalPerson;
use App\Entity\Project;
use App\Enum\InteractionType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class InteractionFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $interactions = [
            // Email interactions
            [
                'type' => InteractionType::EMAIL,
                'dateTime' => new \DateTime('-3 weeks'),
                'subject' => 'Proposition commerciale - Site E-commerce',
                'content' => 'Bonjour,

Je vous fais parvenir notre proposition commerciale pour le développement de votre site e-commerce.
N\'hésitez pas à me contacter pour toute question.

Cordialement,
Équipe Kiora',
                'project' => 'project_1',
                'task' => null,
                'contact' => 'physical_person_1',
                'user' => 'test@test.com',
                'isOutgoing' => true,
                'outcome' => 'Client intéressé, demande de précisions sur le planning',
                'metadata' => ['attachments' => ['proposition_commerce.pdf']]
            ],
            [
                'type' => InteractionType::EMAIL,
                'dateTime' => new \DateTime('-2 weeks 5 days'),
                'subject' => 'RE: Proposition commerciale - Site E-commerce',
                'content' => 'Bonjour,

Merci pour votre proposition. Pourriez-vous me préciser le planning de réalisation ?

Cordialement,
Company 1',
                'project' => 'project_1',
                'task' => null,
                'contact' => 'physical_person_1',
                'user' => 'test@test.com',
                'isOutgoing' => false,
                'outcome' => 'Envoi du planning détaillé',
                'metadata' => []
            ],
            
            // Call interactions
            [
                'type' => InteractionType::CALL,
                'dateTime' => new \DateTime('-2 weeks'),
                'subject' => 'Suivi projet site e-commerce',
                'content' => 'Appel avec le client pour discuter des avancées sur le projet de site e-commerce. Le client est satisfait des wireframes présentés et souhaite avancer rapidement sur la phase de développement.',
                'project' => 'project_1',
                'task' => null,
                'contact' => 'physical_person_1',
                'user' => 'test@test.com',
                'isOutgoing' => true,
                'outcome' => 'Validation des wireframes',
                'metadata' => ['duration' => '25 minutes']
            ],
            
            // Meeting interactions
            [
                'type' => InteractionType::MEETING,
                'dateTime' => new \DateTime('-1 week'),
                'subject' => 'Réunion de lancement projet',
                'content' => 'Réunion de lancement du projet avec l\'équipe client et l\'équipe technique. Points abordés : objectifs, planning, ressources, prochaines étapes.',
                'project' => 'project_1',
                'task' => null,
                'contact' => 'physical_person_1',
                'user' => 'test@test.com',
                'isOutgoing' => true,
                'location' => 'Bureaux Kiora',
                'endDateTime' => new \DateTime('-1 week +2 hours'),
                'outcome' => 'Planning validé, équipe constituée',
                'metadata' => ['participants' => ['Jean Dupont', 'Marie Martin', 'Équipe Kiora']]
            ],
            
            // Task related interactions
            [
                'type' => InteractionType::NOTE,
                'dateTime' => new \DateTime('-3 days'),
                'subject' => 'Note sur développement frontend',
                'content' => 'Le développement frontend avance bien, mais nous avons rencontré quelques difficultés avec les animations sur la page d\'accueil. Nous allons devoir revoir l\'approche pour optimiser les performances.',
                'project' => 'project_1',
                'task' => 2,
                'contact' => null,
                'user' => 'test@test.com',
                'isOutgoing' => true,
                'outcome' => 'Modification planifiée de l\'approche d\'animation',
                'metadata' => []
            ],
            
            // Mobile app project interactions
            [
                'type' => InteractionType::EMAIL,
                'dateTime' => new \DateTime('-2 weeks'),
                'subject' => 'Maquettes application mobile',
                'content' => 'Bonjour,

Veuillez trouver ci-joint les maquettes pour l\'application mobile.
Merci de me faire part de vos retours.

Cordialement,
Équipe Kiora',
                'project' => 'project_2',
                'task' => null,
                'contact' => 'physical_person_4',
                'user' => 'test@test.com',
                'isOutgoing' => true,
                'outcome' => 'Retours positifs sur les maquettes',
                'metadata' => ['attachments' => ['maquettes_mobile_v1.pdf']]
            ],
            
            // Security audit interactions
            [
                'type' => InteractionType::CALL,
                'dateTime' => new \DateTime('-1 week'),
                'subject' => 'Préparation audit sécurité',
                'content' => 'Appel pour préparer l\'audit de sécurité informatique. Définition du périmètre d\'intervention et des modalités.',
                'project' => 'project_3',
                'task' => null,
                'contact' => 'physical_person_5',
                'user' => 'test@test.com',
                'isOutgoing' => true,
                'outcome' => 'Périmètre défini, date d\'intervention fixée',
                'metadata' => ['duration' => '35 minutes']
            ],
            
            // Completed project interactions
            [
                'type' => InteractionType::EMAIL,
                'dateTime' => new \DateTime('-1 month 1 week'),
                'subject' => 'Attestation de formation',
                'content' => 'Bonjour,

Suite à la formation agile dispensée, je vous transmets les attestations de formation pour vos collaborateurs.

Cordialement,
Équipe Kiora',
                'project' => 'project_4',
                'task' => null,
                'contact' => 'physical_person_1',
                'user' => 'test@test.com',
                'isOutgoing' => true,
                'outcome' => 'Formations validées par le client',
                'metadata' => ['attachments' => ['attestations_formation.pdf']]
            ],
            
            // On-hold project interactions
            [
                'type' => InteractionType::MEETING,
                'dateTime' => new \DateTime('-2 weeks'),
                'subject' => 'Réunion reportée - Système de gestion interne',
                'content' => 'La réunion concernant le système de gestion interne a été reportée à la demande du client en raison de contraintes budgétaires temporaires.',
                'project' => 'project_5',
                'task' => null,
                'contact' => 'physical_person_3',
                'user' => 'test@test.com',
                'isOutgoing' => true,
                'location' => 'Visioconférence',
                'endDateTime' => null,
                'outcome' => 'Projet mis en pause pour 2 mois',
                'metadata' => []
            ],
            
            // SMS interaction
            [
                'type' => InteractionType::SMS,
                'dateTime' => new \DateTime('-2 days'),
                'subject' => 'Confirmation rendez-vous',
                'content' => 'Bonjour, je vous confirme notre rendez-vous demain à 14h pour faire le point sur le projet. Cordialement, Team Kiora',
                'project' => 'project_1',
                'task' => null,
                'contact' => 'physical_person_1',
                'user' => 'test@test.com',
                'isOutgoing' => true,
                'outcome' => 'RDV confirmé',
                'metadata' => []
            ],
        ];

        foreach ($interactions as $interactionData) {
            $interaction = new Interaction();
            $interaction->setType($interactionData['type']);
            $interaction->setDateTime($interactionData['dateTime']);
            $interaction->setSubject($interactionData['subject']);
            $interaction->setContent($interactionData['content']);
            
            // Set project reference if available
            if ($interactionData['project']) {
                $interaction->setProject($this->getReference($interactionData['project'], Project::class));
            }
            
            // Set task reference if available
            if ($interactionData['task']) {
                // For tasks, we need to get the task within the fixture
                // This is a simplification: in a real scenario, you'd have task references
                $task = $manager->getRepository('App\Entity\Task')->findOneBy(['id' => $interactionData['task']]);
                if ($task) {
                    $interaction->setTask($task);
                }
            }
            
            // Set contact reference if available
            if ($interactionData['contact']) {
                $interaction->setContact($this->getReference($interactionData['contact'], PhysicalPerson::class));
            }
            
            // Set user (assign to test user)
            $interaction->setUser($this->getUserByEmail($manager, $interactionData['user']));
            
            // Set other properties
            $interaction->setIsOutgoing($interactionData['isOutgoing']);
            
            if (isset($interactionData['location'])) {
                $interaction->setLocation($interactionData['location']);
            }
            
            if (isset($interactionData['endDateTime']) && $interactionData['endDateTime']) {
                $interaction->setEndDateTime($interactionData['endDateTime']);
            }
            
            if (isset($interactionData['outcome'])) {
                $interaction->setOutcome($interactionData['outcome']);
            }
            
            if (!empty($interactionData['metadata'])) {
                $interaction->setMetadata($interactionData['metadata']);
            }
            
            $manager->persist($interaction);
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
            TaskFixtures::class,
            PhysicalPersonFixtures::class,
            LegalPersonFixtures::class,
            UserFixtures::class,
        ];
    }
}