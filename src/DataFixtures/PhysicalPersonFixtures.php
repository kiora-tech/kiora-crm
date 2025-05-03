<?php

namespace App\DataFixtures;

use App\Entity\PhysicalPerson;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PhysicalPersonFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $contacts = [
            [
                'firstName' => 'Jean',
                'lastName' => 'Dupont',
                'email' => 'jean.dupont@example.com',
                'phone' => '0123456789',
                'position' => 'Directeur Général',
                'address' => '123 Avenue des Champs-Élysées, Paris',
                'isPrimaryContact' => true,
                'title' => 'M.',
                'department' => 'Direction',
                'mobile' => '0612345678',
            ],
            [
                'firstName' => 'Marie',
                'lastName' => 'Martin',
                'email' => 'marie.martin@example.com',
                'phone' => '0123456790',
                'position' => 'Responsable Marketing',
                'address' => '456 Rue de Rivoli, Paris',
                'isPrimaryContact' => false,
                'title' => 'Mme',
                'department' => 'Marketing',
                'mobile' => '0687654321',
            ],
            [
                'firstName' => 'Pierre',
                'lastName' => 'Durand',
                'email' => 'pierre.durand@example.com',
                'phone' => '0123456791',
                'position' => 'Directeur Technique',
                'address' => '789 Boulevard Haussmann, Paris',
                'isPrimaryContact' => false,
                'title' => 'M.',
                'department' => 'Technique',
                'mobile' => '0623456789',
            ],
            [
                'firstName' => 'Sophie',
                'lastName' => 'Lefebvre',
                'email' => 'sophie.lefebvre@example.com',
                'phone' => '0123456792',
                'position' => 'Responsable Commercial',
                'address' => '321 Rue Saint-Honoré, Paris',
                'isPrimaryContact' => true,
                'title' => 'Mme',
                'department' => 'Commercial',
                'mobile' => '0698765432',
            ],
            [
                'firstName' => 'Thomas',
                'lastName' => 'Moreau',
                'email' => 'thomas.moreau@example.com',
                'phone' => '0123456793',
                'position' => 'Responsable Financier',
                'address' => '654 Avenue Montaigne, Paris',
                'isPrimaryContact' => false,
                'title' => 'M.',
                'department' => 'Finance',
                'mobile' => '0634567890',
            ],
        ];

        foreach ($contacts as $index => $contactData) {
            $contact = new PhysicalPerson();
            $contact->setFirstName($contactData['firstName']);
            $contact->setLastName($contactData['lastName']);
            $contact->setEmail($contactData['email']);
            $contact->setPhone($contactData['phone']);
            $contact->setPosition($contactData['position']);
            $contact->setAddress($contactData['address']);
            $contact->setIsPrimaryContact($contactData['isPrimaryContact']);
            $contact->setTitle($contactData['title']);
            $contact->setDepartment($contactData['department']);
            $contact->setMobile($contactData['mobile']);

            $manager->persist($contact);
            $this->addReference('physical_person_' . ($index + 1), $contact);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            LegalPersonFixtures::class,
        ];
    }
}