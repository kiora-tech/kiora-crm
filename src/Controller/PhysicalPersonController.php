<?php

namespace App\Controller;

use App\Entity\PhysicalPerson;
use App\Entity\Relation;
use App\Enum\RelationType;
use App\Form\PhysicalPersonType;
use App\Repository\PhysicalPersonRepository;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/clients/personnes-physiques')]
class PhysicalPersonController extends AbstractController
{
    #[Route('/', name: 'physical_person_index', methods: ['GET'])]
    public function index(Request $request, PhysicalPersonRepository $repository, PaginationService $paginationService): Response
    {
        $queryBuilder = $repository->createQueryBuilder('p')
            ->orderBy('p.name', 'ASC');

        $pagination = $paginationService->paginate(
            $queryBuilder,
            $request
        );

        return $this->render('physical_person/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/new', name: 'physical_person_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $person = new PhysicalPerson();
        $form = $this->createForm(PhysicalPersonType::class, $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($person);
            $entityManager->flush();

            $this->addFlash('success', 'physical_person.created_successfully');
            return $this->redirectToRoute('physical_person_index');
        }

        return $this->render('physical_person/new.html.twig', [
            'person' => $person,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'physical_person_show', methods: ['GET'])]
    public function show(PhysicalPerson $person, EntityManagerInterface $entityManager): Response
    {
        // Récupérer les relations de cette personne
        $relationRepository = $entityManager->getRepository(Relation::class);
        $relations = $relationRepository->findAllForPerson($person->getId());
        
        // Récupérer les projets associés à cette personne
        $projects = $person->getProjects();
        
        // Récupérer les interactions associées à cette personne
        $interactions = $person->getInteractions();

        return $this->render('physical_person/show.html.twig', [
            'person' => $person,
            'relations' => $relations,
            'projects' => $projects,
            'interactions' => $interactions
        ]);
    }

    #[Route('/{id}/edit', name: 'physical_person_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PhysicalPerson $person, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PhysicalPersonType::class, $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $person->setUpdatedAt();
            $entityManager->flush();

            $this->addFlash('success', 'physical_person.updated_successfully');
            return $this->redirectToRoute('physical_person_show', ['id' => $person->getId()]);
        }

        return $this->render('physical_person/edit.html.twig', [
            'person' => $person,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'physical_person_delete', methods: ['POST'])]
    public function delete(Request $request, PhysicalPerson $person, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$person->getId(), $request->request->get('_token'))) {
            $entityManager->remove($person);
            $entityManager->flush();
            
            $this->addFlash('success', 'physical_person.deleted_successfully');
        }

        return $this->redirectToRoute('physical_person_index');
    }
    
    #[Route('/{id}/type/{type}', name: 'physical_person_set_type', methods: ['GET'])]
    public function setType(PhysicalPerson $person, string $type, EntityManagerInterface $entityManager): Response
    {
        // Créer une relation entre l'entité de l'utilisateur et cette personne
        $user = $this->getUser();
        $userCompany = $user->getCompany();
        
        // Vérifier si une relation existe déjà
        $relationRepository = $entityManager->getRepository(Relation::class);
        $existingRelation = null;
        
        foreach ($userCompany->getOutgoingRelations() as $relation) {
            if ($relation->getTargetPerson() === $person) {
                $existingRelation = $relation;
                break;
            }
        }
        
        $relationType = RelationType::from(strtoupper($type));
        
        if ($existingRelation) {
            // Mettre à jour le type de relation existant
            $existingRelation->setType($relationType);
        } else {
            // Créer une nouvelle relation
            $relation = new Relation();
            $relation->setSourcePerson($userCompany);
            $relation->setTargetPerson($person);
            $relation->setType($relationType);
            $entityManager->persist($relation);
        }
        
        $entityManager->flush();
        
        $this->addFlash('success', 'physical_person.type_updated');
        
        return $this->redirectToRoute('physical_person_show', ['id' => $person->getId()]);
    }
}