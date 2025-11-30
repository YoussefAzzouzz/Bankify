<?php

namespace App\Controller;

use App\Entity\CompteClient;
use App\Entity\Remboursement;
use App\Form\RemboursementType;
use App\Repository\RemboursementRepository;
use App\Repository\CreditRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Twilio\Rest\Client;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route('/remboursement')]
class RemboursementController extends AbstractController
{
    #[Route('/admin/credit/{id}', name: 'remboursement_admin', methods: ['GET'])]
public function admin(RemboursementRepository $remboursementRepository,CreditRepository $creditRepository,$id): Response
{
    $credit=$creditRepository->findById($id);
    $remboursements = $remboursementRepository->findBy(['credit' => $credit[0]]);
    
    return $this->render('remboursement/adminindex.html.twig', [
        'remboursements' => $remboursements,
        'credit' => $credit[0] // Passer la variable $credit à la vue
    ]);
}

    #[Route('/client/credit/{id}/tri', name: 'tri_remboursements', methods: ['GET'])]
public function trierRemboursements(RemboursementRepository $remboursementRepository, CreditRepository $creditRepository, $id, Request $request,UrlGeneratorInterface $urlGenerator): JsonResponse
{
    $ordre = $request->query->get('ordre');
    $credit = $creditRepository->findById($id);

    $remboursements = $remboursementRepository->triParDate($credit[0], $ordre);


    // Renvoyer les remboursements au format JSON
    $data = [];
    foreach ($remboursements as $remboursement) {
        $data[] = [
            'id' => $remboursement->getId(),
            'credit' => $remboursement->getCredit()->getId(),
            'montantR' => $remboursement->getMontantR(),
            'montantRestant' => $remboursement->getMontantRestant(),
            'dateR' => $remboursement->getDateR() ? $remboursement->getDateR()->format('Y-m-d') : '',
            'dureeRestante' => $remboursement->getDureeRestante(),
            'remboursement_show_client' => $this->generateUrl('remboursement_show_client', ['id' => $remboursement->getId()])
        ];
    }

    return new JsonResponse($data);
}

    #[Route('/client/credit/{id}', name: 'remboursement_client', methods: ['GET'])]
    public function client(RemboursementRepository $remboursementRepository,CreditRepository $creditRepository,$id): Response
    {
        $credit=$creditRepository->findById($id);
        $remboursements = $remboursementRepository->findBy(['credit' => $credit[0]]);
        return $this->render('remboursement/clientindex.html.twig', [
            'remboursements' => $remboursements,
            'credit' => $credit[0]
        ]);
    }

    #[Route('/new/credit/{id}', name: 'remboursement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,CreditRepository $creditRepository,$id): Response
    {
        $remboursement = new Remboursement();
        $form = $this->createForm(RemboursementType::class, $remboursement);
        $form->handleRequest($request);
        $credits=$creditRepository->findById($id);
        $credit=$credits[0];
        $remboursement->setCredit($credit);
        $interet = $credit->getInteret();
        $compte = $credit->getCompte();

        if ($form->isSubmitted() && $form->isValid()) {
            $liste=$credit->getRemboursements();
            $index=count($liste) - 1;
            if($index==-1)
                $remboursement->setMontantRestant($credit->getMontantTotale() - $remboursement->getMontantR());
            else
                $remboursement->setMontantRestant($liste[$index]->getMontantRestant() - $remboursement->getMontantR());            $today = new \DateTime();
            $remboursement->setDateR($today);
            $date = $today->diff($credit->getDateC());
            $remboursement->setdureeRestante($credit->getDureeTotale() - $date->format('%m'));
            $remboursement->setDateR(new \DateTime());
            $montant = $form->get('montantR')->getData();

            if($interet==10 && $montant<500){
                $this->addFlash('danger', 'Le montant doit être supérieur à 500.');
                return $this->redirectToRoute('remboursement_new', ['id' => $id]);
            }
            if($interet==15 && ($montant<300 || $montant>499)){
                $this->addFlash('danger', 'Le montant doit être compris entre 300 et 499.');
                return $this->redirectToRoute('remboursement_new', ['id' => $id]);
            }
            if($interet==20 && ($montant<100 || $montant>299)){
                $this->addFlash('danger', 'Le montant doit être compris entre 100 et 299.');
                return $this->redirectToRoute('remboursement_new', ['id' => $id]);
            }

            $accountSid = 'AC3c608f407fd3e259afc11997317f4308';
            $authToken = '7c7856aeb75828b4320ac400171b092c';
            $twilioNumber = '+14243734278';
            $client = new Client($accountSid, $authToken);
            $clientPhoneNumber = $compte->getTel();
            $message="Succés de remboursement"."\nDe montant ".$remboursement->getMontantR()." DT\nRéalisé le ".$remboursement->getDateR()->format('Y-m-d')."\nIl vous reste ".$remboursement->getMontantRestant()." DT a payé sur les prochaines ".$remboursement->getDureeRestante()." mois";
            $client->messages->create(
                $clientPhoneNumber,
                [
                    'from' => $twilioNumber,
                    'body' => $message
                ]
            );

            $entityManager->persist($remboursement);
            $entityManager->flush();

            return $this->redirectToRoute('remboursement_client', ['id' => $id], Response::HTTP_SEE_OTHER);
        }

        return $this->render('remboursement/new.html.twig', [
            'id'=>$id,
            'remboursement' => $remboursement,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/client/{id}', name: 'remboursement_show_client', methods: ['GET'])]
    public function showClient($id,RemboursementRepository $remboursementRepository): Response
    {
        $remboursement = $remboursementRepository->find($id);
        return $this->render('remboursement/show_client.html.twig', [
            'remboursement' => $remboursement,
        ]);
    }

    #[Route('/admin/{id}', name: 'remboursement_show_admin', methods: ['GET'])]
    public function showAdmin($id,RemboursementRepository $remboursementRepository): Response
    {
        $remboursement = $remboursementRepository->find($id);
        return $this->render('remboursement/show_admin.html.twig', [
            'remboursement' => $remboursement,
        ]);
    }

    #[Route('/pdf/{id}', name: 'remboursement_pdf', methods: ['GET'])]
    public function generatePdf(RemboursementRepository $remboursementRepository,$id): Response
    {
    $remboursements = $remboursementRepository->findById($id);
    $remboursement=$remboursements[0];

    $html = $this->renderView('remboursement/remboursement_pdf.html.twig', [
        'remboursement' => $remboursement,
    ]);

    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    $pdfContent = $dompdf->output();
    return new Response($pdfContent, Response::HTTP_OK, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="remboursement.pdf"',
    ]);}
}