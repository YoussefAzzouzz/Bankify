<?php

namespace App\Controller;

use App\Repository\CategorieAssuranceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontCategorieAssuranceController extends AbstractController
{
    #[Route('/front/categorie/assurance', name: 'app_front_categorie_assurance')]
    public function index(CategorieAssuranceRepository $categorieAssuranceRepository): Response
    {
        $categorieAssurances = $categorieAssuranceRepository->findAll();

        return $this->render('front_categorie_assurance/index.html.twig', [
            'categorie_assurances' => $categorieAssurances,
        ]);
    }
}
