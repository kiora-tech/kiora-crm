<?php

namespace App\Twig\Components;

use App\Entity\Person;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('client_search')]
class ClientSearch
{
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public string $query = '';

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return Person[]
     */
    public function getResults(): array
    {
        if (empty($this->query)) {
            return [];
        }

        /** @var Person[] $result */
        $result = $this->entityManager->getRepository(Person::class)->createQueryBuilder('p')
            ->where('p.name LIKE :query OR p.email LIKE :query OR p.phone LIKE :query OR p.address LIKE :query')
            ->setParameter('query', '%'.$this->query.'%')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();

        return $result;
    }
}
