<?php

namespace App\DataFixtures;

use App\Entity\LegalPerson;
use App\Entity\PhysicalPerson;
use App\Entity\Relation;
use App\Enum\RelationType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class RelationFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // Create a relation between LegalPerson (Kiora) and Company1
        $relation1 = new Relation();
        $relation1->setSourcePerson($this->getReference('legal_person_kiora', LegalPerson::class));
        $relation1->setTargetPerson($this->getReference('legal_person_kiora', LegalPerson::class));
        $relation1->setType(RelationType::CLIENT);
        $relation1->setNotes('Client principal depuis 2020');
        $manager->persist($relation1);
        
        // Create employer relations between Company1 and PhysicalPersons
        for ($i = 1; $i <= 3; $i++) {
            $relation = new Relation();
            $relation->setSourcePerson($this->getReference('legal_person_kiora', LegalPerson::class));
            $relation->setTargetPerson($this->getReference('physical_person_' . $i, PhysicalPerson::class));
            $relation->setType(RelationType::SUBSIDIARY);
            $relation->setNotes('Employé de Company 1');
            $manager->persist($relation);
        }
        
        // Create client relations
        $relation2 = new Relation();
        $relation2->setSourcePerson($this->getReference('legal_person_kiora', LegalPerson::class));
        $relation2->setTargetPerson($this->getReference('physical_person_4', PhysicalPerson::class));
        $relation2->setType(RelationType::CONTACT);
        $relation2->setNotes('Contact établi lors du salon professionnel');
        $manager->persist($relation2);
        
        $relation3 = new Relation();
        $relation3->setSourcePerson($this->getReference('legal_person_kiora', LegalPerson::class));
        $relation3->setTargetPerson($this->getReference('physical_person_5', PhysicalPerson::class));
        $relation3->setType(RelationType::PROSPECT);
        $relation3->setNotes('Prospect intéressé par nos services');
        $manager->persist($relation3);
        
        // Create a parent company relation
        $newCompany = $this->getReference('legal_person_kiora', LegalPerson::class);
        $parentCompany = $this->getReference('legal_person_kiora', LegalPerson::class);
        
        $relation4 = new Relation();
        $relation4->setSourcePerson($newCompany);
        $relation4->setTargetPerson($parentCompany);
        $relation4->setType(RelationType::PARENT_COMPANY);
        $relation4->setNotes('Filiale créée en 2023');
        $manager->persist($relation4);
        
        // Create a supplier relation
        $relation5 = new Relation();
        $relation5->setSourcePerson($this->getReference('legal_person_kiora', LegalPerson::class));
        $relation5->setTargetPerson($this->getReference('physical_person_3', PhysicalPerson::class));
        $relation5->setType(RelationType::SUPPLIER);
        $relation5->setNotes('Fournisseur de services informatiques');
        $manager->persist($relation5);
        
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            LegalPersonFixtures::class,
            PhysicalPersonFixtures::class,
        ];
    }
}