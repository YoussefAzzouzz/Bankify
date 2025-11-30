<?php
namespace App\Controller;

use App\Repository\AssuranceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontAssuranceController extends AbstractController
{
    #[Route('/front/assurance', name: 'app_front_assurance')]
    public function index(AssuranceRepository $assuranceRepository): Response
    {
        $assurances = $assuranceRepository->findAll();

        return $this->render('front_assurance/index.html.twig', [
            'assurances' => $assurances,
            'controller_name' => 'FrontAssuranceController',
        ]);
    }
}
