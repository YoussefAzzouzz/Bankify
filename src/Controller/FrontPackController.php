<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PackRepository;

class FrontPackController extends AbstractController
{
    #[Route('/front/pack', name: 'app_front_pack')]
    public function index(PackRepository $PackRepository): Response
    {
        return $this->render('front_pack/index.html.twig', [
            'packs' => $PackRepository->findAll(),
        ]);
    }
}
