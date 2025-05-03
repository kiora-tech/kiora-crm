<?php

namespace App\DataFixtures;

use App\Entity\LegalPerson;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LegalPersonFixtures extends Fixture
{

    public function load(ObjectManager $manager): void
    {
        $legalPerson = new LegalPerson();
        $legalPerson->setName('Kiora');
        $legalPerson->setAddress('58 Avenue des Champs-Élysées');

        $manager->persist($legalPerson);
        $manager->flush();

        $this->addReference('legal_person_kiora', $legalPerson);

    }
}