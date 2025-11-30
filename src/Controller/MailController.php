<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\EmailService;

class MailController extends AbstractController
{
    private $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    #[Route('/mail', name: 'app_mail')]
    public function index(): Response
    {
        return $this->render('mail/index.html.twig');
    }
    
    #[Route('/send-email', name: 'app_send_email', methods: ['POST'])]
    public function sendEmailAction(Request $request): Response
    {
        $to = 'bbaha413@gmail.com'; // Adresse e-mail prédéfinie du destinataire
        $subject = $request->request->get('subject');
        $body = 'Nom : ' . $request->request->get('name') . "\n" .
                'Sujet : ' . $subject;
    

        // Envoi de l'email
        $this->emailService->sendEmail($to, $subject, $body);
    
        return $this->redirectToRoute('app_assurance_index');
    }
    
}
