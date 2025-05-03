<?php

namespace App\Controller;

use App\Entity\LegalPerson;
use App\Entity\Relation;
use App\Enum\RelationType;
use App\Form\LegalPersonType;
use App\Repository\LegalPersonRepository;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/clients/personnes-morales')]
class LegalPersonController extends AbstractController
{
    #[Route('/', name: 'legal_person_index', methods: ['GET'])]
    public function index(Request $request, LegalPersonRepository $repository, PaginationService $paginationService): Response
    {
        $queryBuilder = $repository->createQueryBuilder('l')
            ->orderBy('l.name', 'ASC');

        $pagination = $paginationService->paginate(
            $queryBuilder,
            $request
        );

        return $this->render('legal_person/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/new', name: 'legal_person_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $person = new LegalPerson();
        $form = $this->createForm(LegalPersonType::class, $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($person);
            $entityManager->flush();

            $this->addFlash('success', 'legal_person.created_successfully');
            return $this->redirectToRoute('legal_person_index');
        }

        return $this->render('legal_person/new.html.twig', [
            'person' => $person,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'legal_person_show', methods: ['GET'])]
    public function show(LegalPerson $person, EntityManagerInterface $entityManager): Response
    {
        // Récupérer les relations de cette personne morale
        $relationRepository = $entityManager->getRepository(Relation::class);
        $relations = $relationRepository->findAllForPerson($person->getId());
        
        // Récupérer les contacts (personnes physiques liées)
        $contacts = $person->getContacts();
        
        // Récupérer les projets associés à cette personne morale
        $projects = $person->getProjects();
        
        // Récupérer les interactions associées à cette personne morale
        $interactions = $person->getInteractions();
        
        // Récupérer les utilisateurs associés à cette personne morale
        $users = $person->getUsers();

        return $this->render('legal_person/show.html.twig', [
            'person' => $person,
            'relations' => $relations,
            'contacts' => $contacts,
            'projects' => $projects,
            'interactions' => $interactions,
            'users' => $users
        ]);
    }

    #[Route('/{id}/edit', name: 'legal_person_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, LegalPerson $person, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LegalPersonType::class, $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $person->setUpdatedAt();
            $entityManager->flush();

            $this->addFlash('success', 'legal_person.updated_successfully');
            return $this->redirectToRoute('legal_person_show', ['id' => $person->getId()]);
        }

        return $this->render('legal_person/edit.html.twig', [
            'person' => $person,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'legal_person_delete', methods: ['POST'])]
    public function delete(Request $request, LegalPerson $person, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$person->getId(), $request->request->get('_token'))) {
            $entityManager->remove($person);
            $entityManager->flush();
            
            $this->addFlash('success', 'legal_person.deleted_successfully');
        }

        return $this->redirectToRoute('legal_person_index');
    }
    
    #[Route('/{id}/type/{type}', name: 'legal_person_set_type', methods: ['GET'])]
    public function setType(LegalPerson $person, string $type, EntityManagerInterface $entityManager): Response
    {
        // Créer une relation entre l'entité de l'utilisateur et cette personne morale
        $user = $this->getUser();
        $userCompany = $user->getCompany();
        
        // Ne pas créer de relation si la personne morale est la même que celle de l'utilisateur
        if ($person->getId() === $userCompany->getId()) {
            $this->addFlash('warning', 'legal_person.cannot_set_relation_to_self');
            return $this->redirectToRoute('legal_person_show', ['id' => $person->getId()]);
        }
        
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
        
        $this->addFlash('success', 'legal_person.type_updated');
        
        return $this->redirectToRoute('legal_person_show', ['id' => $person->getId()]);
    }
}