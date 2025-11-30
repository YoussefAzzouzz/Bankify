<?php

namespace App\Controller;

use App\Entity\TypeC;
use App\Form\TypeCType;
use App\Repository\TypeCRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/typeC')]
class TypeCController extends AbstractController
{
    #[Route('/', name: 'app_typeC_index', methods: ['GET'])]
    public function index(TypeCRepository $typeRepository): Response
    {
        return $this->render('typeC/index.html.twig', [
            'types' => $typeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_typeC_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $type = new TypeC();
        $form = $this->createForm(TypeCType::class, $type);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($type);
            $entityManager->flush();

            return $this->redirectToRoute('app_typeC_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('typeC/new.html.twig', [
            'type' => $type,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_typeC_show', methods: ['GET'])]
    public function show(TypeC $type): Response
    {
        return $this->render('typeC/show.html.twig', [
            'type' => $type,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_typeC_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TypeC $type, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TypeCType::class, $type);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_typeC_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('typeC/edit.html.twig', [
            'type' => $type,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_typeC_delete', methods: ['POST'])]
    public function delete(Request $request, TypeC $type, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$type->getId(), $request->request->get('_token'))) {
            $entityManager->remove($type);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_typeC_index', [], Response::HTTP_SEE_OTHER);
    }
}
