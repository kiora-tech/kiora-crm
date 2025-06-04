<?php

namespace App\Controller;

use App\Entity\LegalPerson;
use App\Entity\Person;
use App\Entity\PhysicalPerson;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/client-dashboard')]
class ClientDashboardController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private PaginationService $paginationService;

    public function __construct(
        EntityManagerInterface $entityManager,
        PaginationService $paginationService
    ) {
        $this->entityManager = $entityManager;
        $this->paginationService = $paginationService;
    }

    #[Route('/', name: 'client_dashboard_index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $allClientsQuery = $this->entityManager->getRepository(Person::class)
            ->createQueryBuilder('p')
            ->orderBy('p.name', 'ASC')
            ->getQuery();

        $companiesQuery = $this->entityManager->getRepository(LegalPerson::class)
            ->createQueryBuilder('lp')
            ->orderBy('lp.name', 'ASC')
            ->getQuery();

        $contactsQuery = $this->entityManager->getRepository(PhysicalPerson::class)
            ->createQueryBuilder('pp')
            ->orderBy('pp.lastName', 'ASC')
            ->getQuery();

        $allClients = $this->paginationService->paginate($allClientsQuery, $request, 10, 'all_page');
        $companies = $this->paginationService->paginate($companiesQuery, $request, 10, 'companies_page');
        $contacts = $this->paginationService->paginate($contactsQuery, $request, 10, 'contacts_page');

        // Statistiques
        $clientsCount = $this->entityManager->getRepository(Person::class)->count([]);
        $companiesCount = $this->entityManager->getRepository(LegalPerson::class)->count([]);
        $contactsCount = $this->entityManager->getRepository(PhysicalPerson::class)->count([]);
        
        // Compter les prospects (personnes avec une relation entrante de type PROSPECT)
        $prospectsCount = $this->entityManager->createQuery(
            'SELECT COUNT(p.id) FROM App\Entity\Person p
             JOIN p.incomingRelations r
             WHERE r.type = :type'
        )->setParameter('type', 'PROSPECT')
         ->getSingleScalarResult();

        return $this->render('client_dashboard/index.html.twig', [
            'allClients' => $allClients,
            'companies' => $companies,
            'contacts' => $contacts,
            'clientsCount' => $clientsCount,
            'companiesCount' => $companiesCount,
            'contactsCount' => $contactsCount,
            'prospectsCount' => $prospectsCount,
        ]);
    }
}