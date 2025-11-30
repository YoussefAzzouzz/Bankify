<?php

namespace App\Controller;

use App\Entity\Virement;
use App\Form\VirementType;
use App\Repository\VirementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;  
use Twig\Environment as TwigEnvironment;

#[Route('/virement')]
class VirementController extends AbstractController
{
    #[Route('/', name: 'app_virement_index', methods: ['GET'])]
    public function index(VirementRepository $virementRepository): Response
    {
        return $this->render('virement/index.html.twig', [
            'virements' => $virementRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_virement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $virement = new Virement();
        $form = $this->createForm(VirementType::class, $virement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($virement);
            $entityManager->flush();

            return $this->redirectToRoute('app_virement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('virement/new.html.twig', [
            'virement' => $virement,
            'form' => $form,
        ]);
    }

    #[Route('/virement/{id}', name: 'app_virement_show', methods: ['GET'])]
    public function show(Virement $virement): Response
    {
        return $this->render('virement/show.html.twig', [
            'virement' => $virement,
        ]);
    }

    #[Route('/virement/{id}/pdf', name: 'app_virement_show_pdf', methods: ['GET'])]
    public function showPdf(Virement $virement, TwigEnvironment $twig): Response
    {
        // Render the Twig template to generate HTML content
        $htmlContent = $twig->render('virement/virement_pdf.html.twig', [
            'virement' => $virement,
        ]);
    
        // Create a DOMPDF instance
        $dompdf = new Dompdf();
        $dompdf->loadHtml($htmlContent);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
    
        // Set response headers for PDF download
        $response = new Response($dompdf->output());
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment; filename="virement.pdf"');
    
        return $response;
    }


    
    private function generatePdfContent(Virement $virement): string
    {
        // Generate PDF content with virement information
        $content = '<html>';
        $content .= '<body>';
        $content .= '<h1>Virement</h1>';
        $content .= '<p>Id: ' . $virement->getId() . '</p>';
        $content .= '<p>Compte Source: ' . $virement->getCompteSource() . '</p>';
        $content .= '<p>Compte Destination: ' . $virement->getCompteDestination() . '</p>';
        $content .= '<p>Montant: ' . $virement->getMontant() . '</p>';
        $content .= '<p>Date: ' . $virement->getDate()->format('Y-m-d') . '</p>';
        $content .= '<p>Heure: ' . ($virement->getHeure() ? $virement->getHeure()->format('H:i:s') : '') . '</p>';
        // Include other virement information fields as needed
    
        $content .= '</body>';
        $content .= '</html>';
    
        return $content;
    }   
     #[Route('/{id}/edit', name: 'app_virement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Virement $virement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(VirementType::class, $virement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_virement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('virement/edit.html.twig', [
            'virement' => $virement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_virement_delete', methods: ['POST'])]
    public function delete(Request $request, Virement $virement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$virement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($virement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_virement_index', [], Response::HTTP_SEE_OTHER);
    }
}