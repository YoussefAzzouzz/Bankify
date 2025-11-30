<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfileType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use App\Security\AppCustomAuthenticator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPassworEncoderInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Image;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(): Response
    {
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }

    #[Route('/modifier', name: 'app_modifier')]
    public function editProfile(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher)
    {
        $user = $this->getUser();
        $form = $this->createForm(ProfileType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $uploadedImage = $form->get('image')->getData();

            if ($uploadedImage) {
                // Generate a unique filename for the uploaded image
                $newFilename = uniqid() . '.' . $uploadedImage->guessExtension();


                // Move the uploaded file to the desired directory
                $uploadedImage->move(
                    $this->getParameter('image_directory'),
                    $newFilename
                );


                // Create a new Image entity and associate it with the user
                $image = new Image();
                $image->setFilename($newFilename);
                $image->setUser($user);

                // Persist the image entity
                $entityManager->persist($image);
            }

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('message', 'Profil mis à jour');
        }

        return $this->render('profile/index.html.twig', [
            'ProfileType' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'user_delete')]
    public function deleteUtilisateurs($id, ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $repo = $em->getRepository(User::class);
        $utilisateur = $repo->find($id);

        if ($utilisateur instanceof User) {
            // Clear user's authentication token
            $this->container->get('security.token_storage')->setToken(null);

            $em->remove($utilisateur);
            $em->flush();

            $this->addFlash('success', 'Utilisateur supprimé avec succès');
        } else {
            $this->addFlash('error', 'Utilisateur introuvable');
        }

        return $this->redirectToRoute('app_landinpage');
    }




    #[Route('/confirm-email/{email}', name: 'confirm_email')]
    public function confirmEmail($email, Request $request, EntityManagerInterface $em): Response
    {
        $repo = $em->getRepository(User::class);
        $utilisateur = $repo->findOneBy(['email' => $email]);
        // If the user is found, update the 'verifi' field
        if ($utilisateur instanceof User) {
            $utilisateur->setIsVerified(1);

            // Persist the changes to the database
            $em->flush();

            // TODO: Redirect to a success page or perform other actions
            return $this->render('blank.html.twig');
        }



        // TODO: Redirect to an error page or perform other actions
        return $this->render('blank.html.twig');
    }
}
