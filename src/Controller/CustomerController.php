<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\Document;
use App\Form\CustomerType;
use App\Form\DropzoneForm;
use App\Repository\CustomerRepository;
use App\Service\ImportService;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/customer')]
class CustomerController extends AbstractController
{
    #[Route('/', name: 'app_customer_index', methods: ['GET'])]
    public function index(CustomerRepository $customerRepository, PaginationService $paginationService, Request $request): Response
    {
        $query = $customerRepository->createQueryBuilder('c')
            ->orderBy('c.name', 'ASC')
            ->getQuery();

        $customers = $paginationService->paginate($query, $request);

        return $this->render('customer/index.html.twig', [
            'customers' => $customers,
        ]);
    }

    #[Route('/new', name: 'app_customer_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $customer = new Customer();
        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($customer);
            $entityManager->flush();

            return $this->redirectToRoute('app_customer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('customer/new.html.twig', [
            'customer' => $customer,
            'form' => $form,
        ]);
    }

    #[Route('/upload', name: 'app_customer_upload', methods: ['GET', 'POST'])]
    public function upload(Request $request, ImportService $importService): Response
    {
        if ($request->isMethod('POST')) {
            /** @var UploadedFile $file */
            $file = $request->files->get('file');

            if ($file && $file->isValid()) {
                $filePath = $file->getPathname();
                $importService->importFromExcel($filePath);

                $this->addFlash('success', 'File uploaded and data imported successfully.');

                return $this->redirectToRoute('app_customer_index');
            }

            $this->addFlash('error', 'There was an issue with the file upload. Please try again.');
        }

        return $this->render('customer/upload.html.twig');
    }

    #[Route('/{id}', name: 'app_customer_show', methods: ['GET', 'POST'])]
    public function show(Request $request, Customer $customer, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);

        $document = new Document();
        $formDocument = $this->createForm(DropzoneForm::class, $document);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Customer updated successfully.');

            // Stay on the same page to reflect updated data
            return $this->redirectToRoute('app_customer_show', ['id' => $customer->getId()]);
        }

        return $this->render('customer/show.html.twig', [
            'customer' => $customer,
            'form' => $form->createView(),
            'formDocument' => $formDocument->createView(),
        ]);
    }


    #[Route('/{id}/edit', name: 'app_customer_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Customer $customer, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_customer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('customer/edit.html.twig', [
            'customer' => $customer,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_customer_delete', methods: ['POST'])]
    public function delete(Request $request, Customer $customer, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $customer->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($customer);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_customer_index', [], Response::HTTP_SEE_OTHER);
    }
}
