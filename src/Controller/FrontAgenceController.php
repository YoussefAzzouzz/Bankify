<?php

namespace App\Controller;

use App\Repository\AgenceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontAgenceController extends AbstractController
{
    #[Route('/front/agence', name: 'app_front_agence')]
    public function index(AgenceRepository $agenceRepository): Response
    {
        $agences = $agenceRepository->findAll();

        return $this->render('front_agence/index.html.twig', [
            'controller_name' => 'FrontAgenceController',
            'agences' => $agences,
        ]);
    }
}
