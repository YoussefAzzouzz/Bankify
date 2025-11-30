<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MacarteController extends AbstractController
{
    #[Route('/macarte', name: 'app_macarte')]
    public function index(): Response
    {
        return $this->render('macarte/index.html.twig', [
            'controller_name' => 'MacarteController',
        ]);
    }
}
