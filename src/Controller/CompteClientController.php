<?php

namespace App\Controller;

use App\Entity\CompteClient;
use App\Entity\Carte;
use App\Entity\Credit;

use App\Repository\CarteRepository;

use App\Form\CompteClientType;
use App\Repository\CompteClientRepository;
use App\Repository\CreditRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/compte/client')]
class CompteClientController extends AbstractController
{
    #[Route('/', name: 'app_compte_client_index', methods: ['GET'])]
    public function index(CompteClientRepository $compteClientRepository): Response
    {
        return $this->render('compte_client/index.html.twig', [
            'compte_clients' => $compteClientRepository->findAll(),
        ]);
    }
    #[Route('/{id}/cartes', name: 'carte_index_for_compte', methods: ['GET'])]
    public function indexForCompte(CompteClient $compte, CarteRepository $carteRepository,CompteClientRepository $CompteClientRepository): Response
    {
    // Récupérer le compte client spécifique
    $compte = $CompteClientRepository->find($compte);

    // Récupérer les cartes associées à ce compte client spécifique
    $cartes = $carteRepository->findBy(['account' => $compte]);

    

    // Rendre la vue avec les informations sur les cartes et le compte client
    return $this->render('carte/indexfront.html.twig', [
        'compte' => $compte,
        'cartes' => $cartes,
    ]);

    }
    #[Route('/{id}/credits', name: 'credit_index_for_compte', methods: ['GET'])]
    public function indexForCompteCredit($id, CreditRepository $creditRepository,CompteClientRepository $CompteClientRepository): Response
    {
    // Récupérer le compte client spécifique
    $compte = $CompteClientRepository->find($id);

    // Récupérer les cartes associées à ce compte client spécifique
    $credits = $creditRepository->findBy(['compte' => $compte]);

    if($credits ==null)
    {$credit=NULL;}
    else
    {$credit=$credits[0];}

    

    // Rendre la vue avec les informations sur les cartes et le compte client
    return $this->render('credit/home.html.twig', [
        'compte' => $compte,
        'credit' => $credit,
    ]);

    }

    #[Route('/new', name: 'app_compte_client_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $compteClient = new CompteClient();
        $form = $this->createForm(CompteClientType::class, $compteClient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($compteClient);
            $entityManager->flush();

            return $this->redirectToRoute('app_compte_client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('compte_client/new.html.twig', [
            'compte_client' => $compteClient,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_compte_client_show', methods: ['GET'])]
    public function show(CompteClient $compteClient): Response
    {
        return $this->render('compte_client/show.html.twig', [
            'compte_client' => $compteClient,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_compte_client_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CompteClient $compteClient, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CompteClientType::class, $compteClient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_compte_client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('compte_client/edit.html.twig', [
            'compte_client' => $compteClient,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_compte_client_delete', methods: ['POST'])]
    public function delete(Request $request, CompteClient $compteClient, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$compteClient->getId(), $request->request->get('_token'))) {
            $entityManager->remove($compteClient);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_compte_client_index', [], Response::HTTP_SEE_OTHER);
    }
}