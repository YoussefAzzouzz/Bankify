<?php

namespace App\Controller;

use App\Entity\Credit;
use App\Form\CreditType;
use App\Repository\CompteClientRepository;
use App\Repository\CreditRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategorieCreditRepository;
use Dompdf\Dompdf;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route('/credit')]
class CreditController extends AbstractController
{
    #[Route('/client', name: 'credit_home')]
    public function home(CreditRepository $creditRepository,CompteClientRepository $compteRepository): Response
    {
        $comptes = $compteRepository->findById(2);//apres integration la commande devient $this->security->getUser()->getCompte();
        $compte = $comptes[0];
        $credits=$creditRepository->findBy(['compte' => $compte]);
        if ($credits!=null){
            $credit=$credits[0];
            $id=$credit->getId();
        } else $id=-1;
        return $this->render('credit/home.html.twig',['id' => $id]);
    }

    #[Route('/admin', name: 'credit_admin')]
    public function admin(): Response
    {
        return $this->render('credit/home.html.twig');
    }

    #[Route('/', name: 'credit_index')]
    public function index(): Response
    {
        return $this->render('credit/index.html.twig');
    }

    #[Route('/accepted', name: 'credit_accepted', methods: ['GET'])]
    public function accepted(CreditRepository $creditRepository, PaginatorInterface $paginator, Request $request): Response
    {
    // Récupérez tous les crédits acceptés mais non payés
    $creditsQuery = $creditRepository->findBy(['accepted' => true, 'payed' => false]);

    // Paginer les résultats
    $credits = $paginator->paginate(
        $creditsQuery, // Requête à paginer
        $request->query->getInt('page', 1), // Numéro de page, 1 par défaut
        1// Nombre d'éléments par page
    );

    return $this->render('credit/accepted.html.twig', [
        'credits' => $credits,
    ]);
    }
    #[Route('/search', name: 'credit_search', methods: ['GET'])]
public function search(Request $request, CreditRepository $creditRepository, UrlGeneratorInterface $urlGenerator): JsonResponse
{
    // Récupérez les crédits filtrés
    $keyword = $request->query->get('keyword');
    $credits = $creditRepository->findByKeyword($keyword);

    // Construisez les URL pour les autres routes
    $creditsWithUrls = [];
    foreach ($credits as $credit) {
        if ($credit->isAccepted() && $credit->isPayed()==false)
            $statut="En cours";
        if ($credit->isAccepted()==false)
            $statut="En attente";
        if ($credit->isPayed())
            $statut="Payé";
        $creditsWithUrls[] = [
            'id' => $credit->getId(),
            'compteId' => $credit->getCompte()->getId(),
            'montantTotale' => $credit->getMontantTotale(),
            'interet' => $credit->getInteret(),
            'categorieNom' => $credit->getCategorie()->getNom(),
            'dateC' => $credit->getDateC()->format('Y-m-d'),
            'dureeTotale' => $credit->getDureeTotale(),
            'statut' => $statut
        ];
    }

    // Retournez les résultats au format JSON avec les URL incluses
    return new JsonResponse(['credits' => $creditsWithUrls]);
}

    #[Route('/demandes', name: 'credits_demandes', methods: ['GET'])]
    public function demandes(CreditRepository $creditRepository,PaginatorInterface $paginator, Request $request): Response
    {
        $creditsQuery = $creditRepository->findBy(['accepted' => false, 'payed' => false]);
        $credits = $paginator->paginate(
            $creditsQuery, // Requête à paginer
            $request->query->getInt('page', 1), // Numéro de page, 1 par défaut
            1// Nombre d'éléments par page
        );
        return $this->render('credit/demandes.html.twig', [
            'credits' => $credits,
        ]);
    }

    #[Route('/payes', name: 'credits_payes', methods: ['GET'])]
    public function payes(CreditRepository $creditRepository,PaginatorInterface $paginator, Request $request): Response
    {
        $creditsQuery = $creditRepository->findBy(['accepted' => true,'payed' => true]);
        $credits = $paginator->paginate(
            $creditsQuery, // Requête à paginer
            $request->query->getInt('page', 1), // Numéro de page, 1 par défaut
            1// Nombre d'éléments par page
        );
        return $this->render('credit/payes.html.twig', [
            'credits' => $credits,
        ]);
    }

    #[Route('/new/{id}', name: 'credit_new', methods: ['GET', 'POST'])]
    public function new($id,Request $request, EntityManagerInterface $entityManager,CategorieCreditRepository $categorieCreditRepository,CompteClientRepository $compteRepository,CreditRepository $creditRepository): Response
    {
        $credit = new Credit();
        $categories = $categorieCreditRepository->findAll();
        $form = $this->createForm(CreditType::class, $credit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $credit->setAccepted(false);
            $credit->setPayed(false);
            $credit->setDateC(new \DateTime());
            $montant = $credit->getMontantTotale() + ($credit->getMontantTotale() * ($credit->getInteret() / 100));
            $credit->setMontantTotale($montant);
            $compte = $compteRepository->find($id);//apres integration la commande devient $this->security->getUser()->getCompte();
            $test=$creditRepository->findBy(['compte' => $compte]);
            if($test==null)
                $credit->setCompte($compte);
            $interet = $form->get('interet')->getData();
            if($interet==10)
                $credit->setDureeTotale(36);
            if($interet==15)
                $credit->setDureeTotale(48);
            if($interet==20)
                $credit->setDureeTotale(60);
                if($test!=null){
                    $this->addFlash('danger', 'Ce compte a déjà un crédit');
                    return $this->redirectToRoute('credit_new',['id' => $id], Response::HTTP_SEE_OTHER);
                }
            $entityManager->persist($credit);
            $entityManager->flush();

            return $this->redirectToRoute('client_show', ['id' => $credit->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('credit/new.html.twig', [
            'credit' => $credit,
            'form' => $form->createView(),
            'categories' => $categories,
        ]);
    }

    #[Route('/{id}/client', name: 'client_show', methods: ['GET'])]
    public function clientShow($id,creditRepository $creditRepository): Response
    {
        $credit=$creditRepository->find($id);
        return $this->render('credit/clientshow.html.twig', [
            'credit' => $credit,
        ]);
    }

    #[Route('/{id}/admin', name: 'admin_show', methods: ['GET'])]
    public function show($id,creditRepository $creditRepository): Response
    {
        $credit=$creditRepository->find($id);
        return $this->render('credit/adminshow.html.twig', [
            'credit' => $credit,
        ]);
    }

    #[Route('/{id}/accept', name: 'acredit_accept', methods: ['GET', 'POST'])]
    public function accept($id,creditRepository $creditRepository, EntityManagerInterface $entityManager): Response
    {
        $credits=$creditRepository->findById($id);
        $credit=$credits[0];
        $credit->setAccepted(true);
        $entityManager->flush();
        return $this->redirectToRoute('credit_accepted', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/delete', name: 'credit_delete', methods: ['GET', 'POST'])]
    public function delete($id,creditRepository $creditRepository, EntityManagerInterface $entityManager): Response
    {
        $credits=$creditRepository->findById($id);
        $credit=$credits[0];
        $accepted=$credit->isAccepted();
        $payed=$credit->isPayed();
        $entityManager->remove($credit);
        $entityManager->flush();
        if($accepted==false && $payed==false)
            return $this->redirectToRoute('credits_demandes', [], Response::HTTP_SEE_OTHER);
        else
            return $this->redirectToRoute('credits_payes', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/pdf/{id}', name: 'credit_pdf', methods: ['GET'])]
    public function generatePdf(CreditRepository $creditRepository,$id): Response
    {
    $credits = $creditRepository->findById($id);
    $credit=$credits[0];
    $html = $this->renderView('credit/credit_pdf.html.twig', [
        'credit' => $credit,
    ]);

    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    $pdfContent = $dompdf->output();
    return new Response($pdfContent, Response::HTTP_OK, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="credit.pdf"',
    ]);}
}
