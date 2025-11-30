<?php

namespace App\Controller;

use App\Entity\CategorieAssurance;
use App\Form\CategorieAssuranceType;
use App\Repository\CategorieAssuranceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/categorie/assurance')]
class CategorieAssuranceController extends AbstractController
{
    #[Route('/', name: 'app_categorie_assurance_index', methods: ['GET'])]
    public function index(CategorieAssuranceRepository $categorieAssuranceRepository): Response
    {
        return $this->render('categorie_assurance/index.html.twig', [
            'categorie_assurances' => $categorieAssuranceRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_categorie_assurance_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $categorieAssurance = new CategorieAssurance();
        $form = $this->createForm(CategorieAssuranceType::class, $categorieAssurance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($categorieAssurance);
            $entityManager->flush();

            return $this->redirectToRoute('app_categorie_assurance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('categorie_assurance/new.html.twig', [
            'categorie_assurance' => $categorieAssurance,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_categorie_assurance_show', methods: ['GET'])]
    public function show(CategorieAssurance $categorieAssurance): Response
    {
        return $this->render('categorie_assurance/show.html.twig', [
            'categorie_assurance' => $categorieAssurance,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_categorie_assurance_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CategorieAssurance $categorieAssurance, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CategorieAssuranceType::class, $categorieAssurance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_categorie_assurance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('categorie_assurance/edit.html.twig', [
            'categorie_assurance' => $categorieAssurance,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_categorie_assurance_delete', methods: ['POST'])]
    public function delete(Request $request, CategorieAssurance $categorieAssurance, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categorieAssurance->getId(), $request->request->get('_token'))) {
            $entityManager->remove($categorieAssurance);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_categorie_assurance_index', [], Response::HTTP_SEE_OTHER);
    }
}
