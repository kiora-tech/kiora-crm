<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\Document;
use App\Form\DropzoneForm;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/document')]
class DocumentController extends AbstractController
{
    #[Route('/{id}', name: 'app_document', methods: ['POST'])]
    public function uploadDocument(
        Request                $request,
        SluggerInterface       $slugger,
        Customer               $customer,
        LoggerInterface        $logger,
        EntityManagerInterface $entityManager,
        #[Autowire('%kernel.project_dir%/public/uploads/documents')]
        string                 $uploadDirectory,
    ): Response
    {
        $document = new Document();
        $form = $this->createForm(DropzoneForm::class, $document);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $documentFile */
            $documentFile = $form->get('file')->getData();

            if ($documentFile) {
                $originalFilename = pathinfo($documentFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $documentFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $documentFile->move($uploadDirectory, $newFilename);

                    $document->setName($originalFilename);
                    $document->setPath($uploadDirectory . '/' . $newFilename);
                    $document->setCustomer($customer);

                    $entityManager->persist($document);
                    $entityManager->flush();
                } catch (FileException $e) {
                    $logger->error('There was an issue with the file upload: ' . $e->getMessage());
                    $this->addFlash('error', 'There was an issue with the file upload. Please try again.');
                }
            }
        }
        $this->redirectToRoute('app_customer_show', ['id' => $customer->getId()]);

        return $this->render('document/upload_response.html.twig', compact('form', 'customer'));
    }
    #[Route('/{id}', name: 'app_document_download', methods: ['GET'])]
    public function downloadDocument(
        Document $document
    ): Response
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $document->getName() . '"');
        $response->setContent(file_get_contents($document->getPath()));

        return $response;
    }

    #[Route('/{id}/remove', name: 'app_document_delete', methods: ['GET'])]
    public function deleteDocument(
        Document $document,
        EntityManagerInterface $entityManager
    ): Response
    {
        //remove the file from the filesystem
        $path = $document->getPath();
        if (file_exists($path)) {
            unlink($path);
        }

        $customer = $document->getCustomer();

        $entityManager->remove($document);
        $entityManager->flush();

        return $this->redirectToRoute('app_document', ['id' => $customer->getId()]);
    }
}