<?php
namespace App\Controller;

use App\Entity\Agence;
use App\Form\AgenceType;
use App\Repository\AgenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\Label\Font\NotoSans;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Endroid\QrCode\ErrorCorrectionLevel;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/agence')]
class AgenceController extends AbstractController
{
    #[Route('/qr-codes/{id}', name: 'app_qr_codes')]
    public function generateQrCode(AgenceRepository $rep, $id): Response
    {
        $specification = $rep->find($id);

        // Extract information from the Agence entity
        $content = [
            'nom_agence' => $specification->getNomAgence(),
            'tel_agence' => $specification->getTelAgence(),
            'email_agence' => $specification->getEmailAgence(),
            // Ajoutez d'autres attributs si nécessaire
        ];

        // Convert the array to a string
        $contentString = json_encode($content);

        $writer = new PngWriter();
        $qrCode = QrCode::create($contentString)
            ->setEncoding(new Encoding('UTF-8'))
            ->setSize(250)
            ->setMargin(0)
            ->setForegroundColor(new Color(0, 0, 0))
            ->setBackgroundColor(new Color(255, 255, 255));

        $qrCodeUri = $writer->write($qrCode)->getDataUri();

        return $this->render('agence/qrcode.html.twig', [
            'qrCodeUri' => $qrCodeUri,
        ]);
    }
    #[Route('/', name: 'app_agence_index', methods: ['GET'])]
    public function index(AgenceRepository $agenceRepository): Response
    {
        return $this->render('agence/index.html.twig', [
            'agences' => $agenceRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_agence_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $agence = new Agence();
        $form = $this->createForm(AgenceType::class, $agence);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($agence);
            $entityManager->flush();
    
            // flash message 
            $this->addFlash('success', 'Agence ajoutée avec succès.');
    
            return $this->redirectToRoute('app_agence_new', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('agence/new.html.twig', [
            'agence' => $agence,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_agence_show', methods: ['GET'])]
    public function show(Agence $agence): Response
    {
        return $this->render('agence/show.html.twig', [
            'agence' => $agence,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_agence_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Agence $agence, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AgenceType::class, $agence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_agence_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('agence/edit.html.twig', [
            'agence' => $agence,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_agence_delete', methods: ['POST'])]
    public function delete(Request $request, Agence $agence, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$agence->getId(), $request->request->get('_token'))) {
            $entityManager->remove($agence);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_agence_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/send-email', name: 'app_agence_send_email', methods: ['GET', 'POST'])]
    public function sendEmail(Request $request, Agence $agence, MailerInterface $mailer): Response
    {
        $form = $this->createFormBuilder()
            ->add('email', EmailType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $email = (new Email())
                ->from('ben.mansour789@gmail.com')
                ->to($data['email'])
                ->subject('Sujet de l\'e-mail')
                ->text('Contenu du message'); // Vous pouvez également utiliser html() pour inclure un contenu HTML

            $mailer->send($email);

            // Redirection ou affichage d'un message de confirmation
        }

        return $this->render('agence/send_email.html.twig', [
            'form' => $form->createView(),
            'agence' => $agence,
        ]);
    }
}