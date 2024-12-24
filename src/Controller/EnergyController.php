<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\Energy;
use App\Form\EnergyType;
use App\Repository\EnergyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/energy')]
final class EnergyController extends AbstractController
{
    #[Route(name: 'app_energy_index', methods: ['GET'])]
    public function index(EnergyRepository $energyRepository): Response
    {
        return $this->render('energy/index.html.twig', [
            'energies' => $energyRepository->findAll(),
        ]);
    }

    #[Route('/new/{customer?}', name: 'app_energy_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, ?Customer $customer = null): Response
    {
        $energy = new Energy();
        $energy->setCustomer($customer);
        $form = $this->createForm(EnergyType::class, $energy, ['customer' => $customer]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($energy);
            $entityManager->flush();

            if ($customer) {
                return $this->redirectToRoute('app_customer_show', ['id' => $customer->getId()],
                    Response::HTTP_SEE_OTHER);
            }

            return $this->redirectToRoute('app_energy_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('energy/new.html.twig', [
            'energy' => $energy,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_energy_show', methods: ['GET'])]
    public function show(Energy $energy): Response
    {
        return $this->render('energy/show.html.twig', [
            'energy' => $energy,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_energy_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Energy $energy, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EnergyType::class, $energy);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_energy_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('energy/edit.html.twig', [
            'energy' => $energy,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_energy_delete', methods: ['POST'])]
    public function delete(Request $request, Energy $energy, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$energy->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($energy);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_energy_index', [], Response::HTTP_SEE_OTHER);
    }
}
