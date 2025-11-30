<?php

namespace App\Controller;

use App\Entity\Assurance;
use App\Form\AssuranceType;
use App\Repository\AssuranceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/assurance')]
class AssuranceController extends AbstractController
{
    #[Route('/', name: 'app_assurance_index', methods: ['GET'])]
    public function index(AssuranceRepository $assuranceRepository): Response
    {
        return $this->render('assurance/index.html.twig', [
            'assurances' => $assuranceRepository->findAll(),
        ]);
    }

    #[Route('/pdf', name: 'app_assurance_pdf', methods: ['GET'])]
    public function generatePdf(AssuranceRepository $assuranceRepository): Response
    {
        $assurances = $assuranceRepository->findAll();

        // Generate the HTML content of the PDF with the specific template for the list of assurances
        $html = $this->renderView('assurance/pdf_template.html.twig', [
            'assurances' => $assurances,
        ]);

        // Create an instance of Dompdf with the necessary options
        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $dompdf = new \Dompdf\Dompdf($options);

        // Load the HTML content into Dompdf
        $dompdf->loadHtml($html);

        // Render the PDF
        $dompdf->render();

        // Send the PDF as an HTTP response
        return new Response($dompdf->output(), Response::HTTP_OK, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    #[Route('/new', name: 'app_assurance_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $assurance = new Assurance();
        $form = $this->createForm(AssuranceType::class, $assurance);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($assurance);
            $entityManager->flush();
    
            //  flash message 
            $this->addFlash('success', 'Assurance ajoutée avec succès.');
    
            return $this->redirectToRoute('app_assurance_new', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('assurance/new.html.twig', [
            'assurance' => $assurance,
            'form' => $form,
        ]);
    }
    

    #[Route('/{id}', name: 'app_assurance_show', methods: ['GET'])]
    public function show(Assurance $assurance): Response
    {
        return $this->render('assurance/show.html.twig', [
            'assurance' => $assurance,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_assurance_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Assurance $assurance, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AssuranceType::class, $assurance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_assurance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('assurance/edit.html.twig', [
            'assurance' => $assurance,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_assurance_delete', methods: ['POST'])]
    public function delete(Request $request, Assurance $assurance, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$assurance->getId(), $request->request->get('_token'))) {
            $entityManager->remove($assurance);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_assurance_index', [], Response::HTTP_SEE_OTHER);
    }
}