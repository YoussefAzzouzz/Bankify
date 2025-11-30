<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TypeRepository;

class FrontTypeController extends AbstractController
{
    #[Route('/front/type', name: 'app_front_type')]
    public function index(TypeRepository $typeRepository): Response
    {
        return $this->render('front_type/index.html.twig', [
            'types' => $typeRepository->findAll(),
        ]);
    }
}
