<?php

namespace App\Controller;

use App\Entity\CategorieCredit;
use App\Form\CategorieCreditType;
use App\Repository\CategorieCreditRepository;
use App\Repository\CreditRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/categorie')]
class CategorieCreditController extends AbstractController
{
    #[Route('/', name: 'categorie_index', methods: ['GET'])]
    public function index(CategorieCreditRepository $categorieCreditRepository,PaginatorInterface $paginator,Request $request): Response
    {
        $categorieQuery = $categorieCreditRepository->findAll();
        $statistiques = $categorieCreditRepository->getStatistiques();
        $categorie = $paginator->paginate(
            $categorieQuery, // Requête à paginer
            $request->query->getInt('page', 1), // Numéro de page, 1 par défaut
            2// Nombre d'éléments par page
        );
        return $this->render('categorie_credit/index.html.twig', [
            'categorie_credits' => $categorie,
            'statistiques' => $statistiques,
        ]);
    }

    #[Route('/new', name: 'categorie_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $categorieCredit = new CategorieCredit();
        $form = $this->createForm(CategorieCreditType::class, $categorieCredit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($categorieCredit);
            $entityManager->flush();

            return $this->redirectToRoute('categorie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('categorie_credit/new.html.twig', [
            'categorie_credit' => $categorieCredit,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'categorie_show', methods: ['GET'])]
    public function show(CategorieCreditRepository $categorieCredit,CreditRepository $creditRepository,$id,PaginatorInterface $paginator,Request $request): Response
    {
        $categorieQuery = $creditRepository->findBy(['categorie' => $categorieCredit->findById($id)]);
        $categorie = $paginator->paginate(
            $categorieQuery, // Requête à paginer
            $request->query->getInt('page', 1), // Numéro de page, 1 par défaut
            1// Nombre d'éléments par page
        );
        return $this->render('categorie_credit/show.html.twig', [
            'credits' => $categorie,
        ]);
    }

    #[Route('/{id}/edit', name: 'categorie_edit', methods: ['GET', 'POST'])]
    public function edit($id,CategorieCreditRepository $categorieCreditRepository,Request $request, EntityManagerInterface $entityManager): Response
    {
        $categorieCredit=$categorieCreditRepository->find($id);
        $form = $this->createForm(CategorieCreditType::class, $categorieCredit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('categorie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('categorie_credit/edit.html.twig', [
            'categorie_credit' => $categorieCredit,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'categorie_delete', methods: ['POST'])]
    public function delete(Request $request, $id,CategorieCreditRepository $categorieCreditRepository, EntityManagerInterface $entityManager): Response
    {
        $categorieCredit=$categorieCreditRepository->find($id);
        if ($this->isCsrfTokenValid('delete'.$categorieCredit->getId(), $request->request->get('_token'))) {
            $entityManager->remove($categorieCredit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('categorie_index', [], Response::HTTP_SEE_OTHER);
    }
}
