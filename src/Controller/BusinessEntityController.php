<?php

namespace App\Controller;

use App\Entity\BusinessEntity;
use App\Entity\Customer;
use App\Form\BusinessEntityType;
use App\Repository\BusinessEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/business/entity')]
final class BusinessEntityController extends AbstractController
{
    #[Route(name: 'app_business_entity_index', methods: ['GET'])]
    public function index(BusinessEntityRepository $businessEntityRepository): Response
    {
        return $this->render('business_entity/index.html.twig', [
            'business_entities' => $businessEntityRepository->findAll(),
        ]);
    }

    #[Route('/new/{customer?}', name: 'app_business_entity_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, ?Customer $customer = null): Response
    {
        $businessEntity = new BusinessEntity();
        $businessEntity->setCustomer($customer);
        $form = $this->createForm(BusinessEntityType::class, $businessEntity, ['customer' => $customer]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($businessEntity);
            $entityManager->flush();

            if ($customer) {
                return $this->redirectToRoute('app_customer_show', ['id' => $customer->getId()],
                    Response::HTTP_SEE_OTHER);
            }

            return $this->redirectToRoute('app_business_entity_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('business_entity/new.html.twig', [
            'business_entity' => $businessEntity,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_business_entity_show', methods: ['GET'])]
    public function show(BusinessEntity $businessEntity): Response
    {
        return $this->render('business_entity/show.html.twig', [
            'business_entity' => $businessEntity,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_business_entity_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, BusinessEntity $businessEntity, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BusinessEntityType::class, $businessEntity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_business_entity_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('business_entity/edit.html.twig', [
            'business_entity' => $businessEntity,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_business_entity_delete', methods: ['POST'])]
    public function delete(Request $request, BusinessEntity $businessEntity, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$businessEntity->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($businessEntity);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_business_entity_index', [], Response::HTTP_SEE_OTHER);
    }
}
