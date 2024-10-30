<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\User;
use App\Enum\ClientDocumentType;
use App\Form\AssignedCollaboratorsType;
use App\Form\ClientType;
use App\Repository\ClientRepository;
use App\Repository\TemplateRepository;
use App\Services\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/client', name: 'app_client_')]
class ClientController extends AbstractController
{
    /**
     * @param PaginationService<int, mixed> $paginationService
     */
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(ClientRepository $repository, PaginationService $paginationService, Request $request): Response
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw new \LogicException('The user is not valid.');
        }

        $query = $repository->findAssignedClients($user);

        $clients = $paginationService->paginate($query, $request);

        return $this->render('client/index.html.twig', [
            'clients' => $clients,
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, TranslatorInterface $tr): Response
    {
        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        $user = $this->getUser();
        if (!$user instanceof User) {
            throw new \LogicException('The user is not valid.');
        }
        $client->addAssignedCollaborator($user);

        if ($form->isSubmitted() && $form->isValid()) {
            $documents = [];

            foreach ($client->getSupportingDocuments() as $document) {
                $documents[] = $document;
                $client->removeSupportingDocument($document);
            }

            $entityManager->persist($client);
            $entityManager->flush();

            foreach ($documents as $document) {
                $document->setClient($client);
                $entityManager->persist($document);
            }

            $entityManager->flush();

            $message = $tr->trans('client.success_message.create', [
                '%name%' => $client->getFullName(),
                '%link%' => sprintf('<a href="%s">%s</a>', $this->generateUrl('app_client_show', ['id' => $client->getId()]), $tr->trans('client.view_profile')),
            ]);
            $this->addFlash('success', $message);

            return $this->redirectToRoute('app_client_index');
        }

        return $this->render('client/new.html.twig', [
            'client' => $client,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Client $client, TemplateRepository $templateRepository): Response
    {
        $response = $this->checkClientAccess($client);
        if (null !== $response) {
            return $response;
        }

        $templates = $templateRepository->getTemplatesByType(ClientDocumentType::PROSPECT);

        return $this->render('client/show.html.twig', [
            'client' => $client,
            'financialAssets' => $client->getFinancialAsset(),
            'nonFinancialAssets' => $client->getNonFinancialAssets(),
            'liabilities' => $client->getLiabilities(),
            'revenues' => $client->getRevenues(),
            'expenses' => $client->getExpenses(),
            'powensUser' => $client->getClientLogin()?->getPowensUser(),
            'templates' => $templates,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', requirements: ['id' => Requirement::POSITIVE_INT], methods: ['GET', 'POST'])]
    public function edit(Client $client, Request $request, EntityManagerInterface $em, TranslatorInterface $tr): Response
    {
        $response = $this->checkClientAccess($client);
        if (null !== $response) {
            return $response;
        }

        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', $tr->trans('client.success_message.modified'));

            return $this->redirectToRoute('app_client_index');
        }

        return $this->render('client/edit.html.twig', [
            'form' => $form,
            'client' => $client,
        ]);
    }

    #[Route('/{id}', name: 'delete', requirements: ['id' => Requirement::POSITIVE_INT], methods: ['DELETE'])]
    public function delete(Client $client, EntityManagerInterface $em, TranslatorInterface $tr): Response
    {
        $response = $this->checkClientAccess($client);
        if (null !== $response) {
            return $response;
        }

        if (null !== $client->getDeletedAt()) {
            throw $this->createNotFoundException('The client is already deleted.');
        }

        $em->remove($client);
        $em->flush();

        $this->addFlash('success', $tr->trans('client.success_message.delete'));

        return $this->redirectToRoute('app_client_index');
    }

    #[Route('/{id}/edit-collaborators', name: 'edit_collaborators')]
    public function editCollaborators(Client $client, Request $request, EntityManagerInterface $entityManager): Response
    {
        $response = $this->checkClientAccess($client);
        if (null !== $response) {
            return $response;
        }

        $form = $this->createForm(AssignedCollaboratorsType::class, null, [
            'data' => [
                'assignedCollaborators' => $client->getAssignedCollaborators(),
            ],
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_client_show', ['id' => $client->getId()]);
        }

        return $this->render('client/edit_collaborators.html.twig', [
            'form' => $form,
        ]);
    }
}
