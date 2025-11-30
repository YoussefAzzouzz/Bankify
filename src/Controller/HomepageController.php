<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class HomepageController extends AbstractController
{
    #[Route('/', name: 'app_landinpage')]
    public function landin_page(): Response
    {
        return $this->render('homepage/landinpage.html.twig', [
            'controller_name' => 'HomepageController',
        ]);
    }

    #[Route('/homepage', name: 'app_homepage')]
    public function homepage(): Response
    {
        return $this->render('homepage/homepage.html.twig', [
            'controller_name' => 'HomepageController',
        ]);
    }
    #[Route('/user', name: 'app_user')]
    public function index2(): Response
    {
        $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");

        /** @var User $user */
        $user = $this->getUser();

        if ($user->isVerified()) {
            return $this->render("homepage/homepage.html.twig");
        } else {
            return $this->render("user/please-verify-email.html.twig");
        }
    }
    #[Route('/home/page', name: 'app_home_page')]
    public function index(): Response
    {
        return $this->render('home_page/index.html.twig', [
            'controller_name' => 'HomePageController',
        ]);
    }
}