<?php

namespace App\Controller;

use App\Entity\CategorieAssurance; 
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontCategorieShowController extends AbstractController
{
    #[Route('/front/categorie/show', name: 'app_front_categorie_show')]
    public function index(): Response
    {
        
        $categoriesAssurance = $this->getDoctrine()->getRepository(CategorieAssurance::class)->findAll();

        return $this->render('front_categorie_show/index.html.twig', [
            'categories_assurance' => $categoriesAssurance,
            'controller_name' => 'FrontCategorieShowController',
        ]);
    }
}
