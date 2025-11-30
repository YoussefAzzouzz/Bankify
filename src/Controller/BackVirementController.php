<?php

namespace App\Controller;
use App\Repository\VirementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BackVirementController extends AbstractController
{
    #[Route('/back/virement', name: 'app_back_virement')]
    public function index(VirementRepository $virementRepository): Response
    {
        return $this->render('back_virement/index.html.twig', [
            'virements' => $virementRepository->findAll(),
        ]);
    }
}
