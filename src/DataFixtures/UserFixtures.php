<?php

namespace App\DataFixtures;

use App\Entity\LegalPerson;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setFirstName('test');
        $user->setLastName('test1');
        $user->setEmail('test@test.com');
        $user->setPassword($this->passwordHasher->hashPassword(
            $user,
            'password'
        ));
        $user->setCompany($this->getReference('legal_person_kiora', LegalPerson::class));

        $manager->persist($user);
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            LegalPersonFixtures::class,
        ];
    }
}
