<?php

namespace App\Controller;

use DateTimeImmutable; // Import DateTimeImmutable
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\ChequeRepository;
use App\Repository\ReclamtionRepository;
use App\Form\RecType;
use App\Form\Rec1Type;
use App\Form\Rec2Type;
use App\Form\ChequeType;
use App\Form\CheqType;
use App\Form\ReclamtionType;
use App\Entity\Cheque;
use App\Entity\Reclamtion;
use App\Entity\CompteClient;
use Symfony\Component\HttpFoundation\Request;
use Dompdf\Dompdf;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Form\FormError;
use Psr\Log\LoggerInterface;




use Dompdf\Options;


class ChequeController extends AbstractController
{  private $flashBag;

   
    private $logger;

    public function __construct(LoggerInterface $logger,FlashBagInterface $flashBag)
    {
        $this->logger = $logger;
        $this->flashBag = $flashBag;

    }

 

    #[Route('/cheques', name: 'cheque_list')]
    public function list(ChequeRepository $chequeRepository, ReclamtionRepository $ReclamtionRepository)
    {
        $cheques = $chequeRepository->findAll();
        $Reclamtions = $ReclamtionRepository->findAll();
        $randomNumber = mt_rand(1000, 9999);

        return $this->render("cheque/back/listcheques.html.twig", [
            "tabcheque" => $cheques,
            "tab" => $Reclamtions,
            'random' => $randomNumber,
        ]);
    }
    #[Route('/cheques/{ref}', name: 'cheque_list1')]                                                                   //front
    public function list1(ChequeRepository $chequeRepository, $ref, ReclamtionRepository $ReclamtionRepository)
    {
        $cheques = $chequeRepository->findByCompteID($ref);
        $Reclamtions = $ReclamtionRepository->findAll();
        $randomNumber = mt_rand(1000, 9999); // Generate a random number between 1000 and 9999




        return $this->render("cheque/front/list.html.twig", [
            "tabcheque" => $cheques,
            "tab" => $Reclamtions,
            "ref" => $ref,

            'random' => $randomNumber,
        ]);
    }

    #[Route('/cheques1/{ref}', name: 'cheque_list11')]
    public function list1111(ChequeRepository $chequeRepository, $ref, ReclamtionRepository $ReclamtionRepository, Request $request, ManagerRegistry $managerRegistry)
    {    
        if ($request->headers->get('content-type') === 'application/json' && $request->isMethod('POST')) {
            // Get the data sent via POST
            $data = json_decode($request->getContent(), true);
        
            // Log the received data for verification
            // Log the received data for verification
      $this->logger->info('Received data:', $data);

        
            // Assuming you're sending the isFav value via POST
            $isFav = $data['isFav'];
            $ID = $data['itemId'];
            var_dump($isFav);   
            
            $cheque = $chequeRepository->find($ID);
            $cheque->setIsfav($isFav);
            $entityManager = $managerRegistry->getManager();
        
            $entityManager->flush();
        
            return new JsonResponse(['success' => true]);
        }

      
    
        // If it's not an AJAX POST request, continue with the regular rendering logic
        $cheques = $chequeRepository-> findByCompteIDAndIsFav($ref);
        $Reclamtions = $ReclamtionRepository->findAll();
        $randomNumber = mt_rand(1000, 9999); // Generate a random number between 1000 and 9999
    
        return $this->render("cheque/front/listt.html.twig", [
            "tabcheque" => $cheques,
            "tab" => $Reclamtions,
            "ref" => $ref,
            'random' => $randomNumber,
        ]);
    }
    










    /**
     * @Route("/ajoutercheque", name="ajoutercheque")
     */
    public function ajoutercheque(Request $request, ManagerRegistry $managerRegistry): Response
    { 
        
        $cheque = new Cheque();
        $form = $this->createForm(ChequeType::class, $cheque);
        $randomNumber = mt_rand(1000, 9999);
       $cheque->setIsfav(0);
        $form->handleRequest($request);
        $entityManager = $managerRegistry->getManager();


        if ($form->isSubmitted() && $form->isValid()) {
           
          
            $entityManager->persist($cheque);
            $entityManager->flush();
            return $this->redirectToRoute('cheque_list');}
        

        return $this->render('cheque/back/index.html.twig', [
            'cheque' => $form->createView(),
            'random' => $randomNumber,
            
          
        ]);
    }
    /**
     * @Route("/addRec/{ref}", name="ajouterreclamtion1")
     */
    public function ajouterreclamtion1(Request $request, $ref, ManagerRegistry $managerRegistry, SessionInterface $session): Response
    {
        $reclamation = new Reclamtion();
        $entityManager = $managerRegistry->getManager();
        $ch = $entityManager->getRepository(Cheque::class)->find($ref);
        $randomNumber = mt_rand(1000, 9999);



        $form = $this->createForm(ReclamtionType::class, $reclamation);
        $reclamation->setChequeID($ch);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reclamation);
            $entityManager->flush();

            $REC1 = $session->get('REC1', []);
            $REC1[] = $reclamation;
            $session->set('REC1', $REC1);

            return $this->redirectToRoute('cheque_list');
        }

        return $this->render('cheque/back/AddRec.html.twig', [
            'reclamation' => $form->createView(),
            'random' => $randomNumber,

        ]);
    }
    /**
     * @Route("/addenc1/{ref}", name="ajouterreclamtion2")                                                   //front
     */
    public function ajouterreclamtion11(Request $request, $ref, ManagerRegistry $managerRegistry, SessionInterface $session): Response
    {
        $reclamtion = new reclamtion();
        $entityManager = $managerRegistry->getManager();
        $ch = $entityManager->getRepository(Cheque::class)->find($ref);


        $c = $ch->getCompteId();


        $form = $this->createForm(RecType::class, $reclamtion);
        $reclamtion->setChequeId($ch);
        $reclamtion->setStatutR('En cours');

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reclamtion);
            $entityManager->flush();

            $REC1 = $session->get('REC1', []);
            $REC1[] = $reclamtion;
            $session->set('REC1', $REC1);



            return $this->redirectToRoute('cheque_list1', ['ref' => $c->getId()]);
        }




        return $this->render('cheque/front/AddRe.html.twig', [
            'reclamtion' => $form->createView(),


        ]);
    }
    /**
     * @Route("/ajoutercheque1/{ref}", name="ajoutercheque1")                                                      //front
     */
    public function ajoutercheque1(Request $request, $ref, ManagerRegistry $managerRegistry): Response
    {
        $entityManager = $managerRegistry->getManager();
        
        // Find the compte by its ID
        $compte = $entityManager->getRepository(CompteClient::class)->find($ref);

        // Find all cheques related to the compte
        $cheques = $entityManager->getRepository(Cheque::class)->findByCompteID($ref);

        // Calculate the total sum of amounts of cheques
        $sommeCheques = 0;
        foreach ($cheques as $cheque) {
            $sommeCheques += $cheque->getMontantC();
        }

        // Create a new Cheque instance
        $cheque = new Cheque();
        $cheque->setCompteId($compte);
        $cheque->setIsfav(0);
        $cheque->setDateEmission(new DateTime());

        // Create the form
        $form = $this->createForm(CheqType::class, $cheque);

        // Handle the form submission
        $form->handleRequest($request);
        $diff=(($compte->getSolde()) - $sommeCheques);
        if($diff<0){$diff=0;}

        if ($form->isSubmitted() ) {
       
          
            if (($cheque->getMontantC()) > $diff) {
         
                // Set a custom error message directly on the form
                $form->get('montantC')->addError(new FormError('Insufficient balance. Please enter a lower amount.'));
            } else {
                // Persist and flush the new cheque
                $entityManager->persist($cheque);
                $entityManager->flush();

                // Redirect to the cheque list page
                return $this->redirectToRoute('cheque_list1', ['ref' => $ref]);
            }
        }

        // Render the form with or without error message
        return $this->render('cheque/front/addCheque.html.twig', [
            'cheque' => $form->createView(),
            'a' => $diff,
            's' => $sommeCheques,
       

        ]);
    }






    /**
     * @Route("/a2/{ref}", name="a1")
     */
    public function emptyRECTraitée1(SessionInterface $session, $ref, ManagerRegistry $managerRegistry): Response
    {

        $session->remove('RECTraitée');

        // Optionally, you can redirect the user to another page after emptying the array
        return $this->redirectToRoute('cheque_list1', ['ref' => $ref]);
    }
    /**
     * @Route("/a1", name="a2")
     */
    public function emptyRECTraitée1f(SessionInterface $session, ManagerRegistry $managerRegistry): Response
    {

        $session->remove('REC1');

        // Optionally, you can redirect the user to another page after emptying the array
        return $this->redirectToRoute('cheque_list');
    }
    /**
     * @Route("/a/{ref}", name="a11")
     */
    public function emptyRECTraitée(SessionInterface $session, $ref, ManagerRegistry $managerRegistry): Response
    {
        $entityManager = $managerRegistry->getManager();
        $ch = $entityManager->getRepository(Cheque::class)->find($ref);
        $c = $ch->getCompteId();

        if ($session->has('RECTraitée')) {
            // Retrieve the RECTraitée session variable
            $RECTraitée = $session->get('RECTraitée');

            // Filter out all Reclamtion objects with the given chequeID
            $RECTraitée = array_filter($RECTraitée, function ($rec) use ($ref) {
                return $rec->getChequeID()->getId() != $ref;
            });

            // Update the RECTraitée session variable
            $session->set('RECTraitée', $RECTraitée);
        }

        // Optionally, you can redirect the user to another page after emptying the array
        return $this->redirectToRoute('cheque_list1', ['ref' => $c->getId()]);
    }
  
    /**
     * @Route("/rec/{ref}", name="rec_edit")
     */
    public function edit(Request $request, $ref, ManagerRegistry $managerRegistry, SessionInterface $session,MailerInterface $mailer): Response
    {

        $entityManager = $managerRegistry->getManager();
        $rec = $entityManager->getRepository(reclamtion::class)->find($ref);
        $response = $request->request->get('response');
        $randomNumber = mt_rand(1000, 9999);


        $form = $this->createForm(Rec2Type::class, $rec);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $rec = $entityManager->getRepository(reclamtion::class)->find($ref);

            if ($rec->getStatutR() === 'Traitée') {
                $RECTraitée = $session->get('RECTraitée', []); // Load existing array from session or initialize as empty array
            
                // Check if $rec already exists in $RECTraitée
                $exists = false;
                foreach ($RECTraitée as $existingRec) {
                    if ($existingRec->getId() === $rec->getId()) {
                        $exists = true;
                        break;
                    }
                }
            
                // If $rec doesn't already exist, append it to $RECTraitée
                if (!$exists) {
                    $RECTraitée[] = $rec;
                    $session->set('RECTraitée', $RECTraitée); // Save the updated array in the session
                }

                if ($response !== null) {
                    // Create the email
                    $email = (new Email())
                        ->from(new Address('nexus@gmail.com', 'Nexus'))
                        ->to($rec->getChequeID()->getCompteID()->getUserID()->getEmail())
                        ->subject('Reclamation du cheque ' . $rec->getChequeID()->getId() . ' a ete traitée')
                        ->html('
                            <!DOCTYPE html>
                            <html lang="en">
                            <head>
                                <meta charset="UTF-8">
                                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                                <title>Email Content</title>
                                <style>
                                    /* Add your CSS styles here */
                                    body {
                                        font-family: Arial, sans-serif;
                                        background-color: #f4f4f4;
                                        color: #333;
                                    }
                                    .email-container {
                                        max-width: 600px;
                                        margin: 0 auto;
                                        padding: 20px;
                                        background-color: #fff;
                                        border-radius: 10px;
                                        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                                    }
                                    .response {
                                        font-size: 16px;
                                        line-height: 1.6;
                                        margin-bottom: 20px;
                                    }
                                </style>
                            </head>
                            <body>
                                <div class="email-container">
                                    <h2>Reclamation du cheque ' . $rec->getChequeID()->getId() . ' a ete traitée</h2>
                                    <p class="response">' . $response . '</p>
                                  
                                </div>
                            </body>
                            </html>
                        ');
                
                    // Send the email
                    $mailer->send($email);
                
                    // Flash message
                    $this->flashBag->add('success', 'Email sent successfully to ' . $rec->getChequeID()->getCompteID()->getUserID()->getEmail());
                }
                
                
            }





            return $this->redirectToRoute('cheque_list');
        }

        return $this->renderForm('cheque/back/editRec.html.twig', [
            'rec' => $form,
            'random' => $randomNumber,


        ]);
    }
    /**
     * @Route("/editchequee/{ref}", name="ch_edit")
     */
    public function edit1(Request $request, $ref, ManagerRegistry $managerRegistry): Response
    {
        $entityManager = $managerRegistry->getManager();
        $ch = $entityManager->getRepository(Cheque::class)->find($ref);
        $reff=$ch->getCompteId()->getId();
        $entityManager = $managerRegistry->getManager();
        $compte = $entityManager->getRepository(CompteClient::class)->find($reff);
        $cheques = $entityManager->getRepository(Cheque::class)->findByCompteID($reff);
     $sommeCheques = 0;
    foreach ($cheques as $cheque) {
        $sommeCheques += $cheque->getMontantC();
    }
    $newsomme=$sommeCheques-$ch->getMontantC();
        $randomNumber = mt_rand(1000, 9999);

        if (!$ch) {
            throw $this->createNotFoundException('No cheque found for id ' . $ref);
        }

        $form = $this->createForm(ChequeType::class, $ch);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($ch->getMontantC()> $compte->getSolde() -  $newsomme) {
                // Set a custom error message directly on the form
                $form->get('montantC')->addError(new FormError('Insufficient balance. Please enter a lower amount.'));
            } else {
              
            $entityManager->flush();

            return $this->redirectToRoute('cheque_list');
        }}

        return $this->renderForm('cheque/back/edit.html.twig', [
            'cheque' => $form,
            'random' => $randomNumber,
        ]);
    }
    /**
     * @Route("/editchequeee/{ref}", name="ch1_edit")                                                             //front
     */
    public function edit11(Request $request, $ref, ManagerRegistry $managerRegistry): Response //front
    {
        $entityManager = $managerRegistry->getManager();
        $ch = $entityManager->getRepository(Cheque::class)->find($ref);
        $c = $ch->getCompteId();
        $ref=$c->getId();
        
    $cheques = $entityManager->getRepository(Cheque::class)->findByCompteID($ref);
    $sommeCheques = 0;
    $s=$c->getSolde();
    foreach ($cheques as $cheque) {
        $sommeCheques += $cheque->getMontantC();
    }
    $newsomme=$sommeCheques-$ch->getMontantC();
      
       



        $form = $this->createForm(CheqType::class, $ch);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($ch->getMontantC()> $c->getSolde() -  $newsomme) {
                // Set a custom error message directly on the form
                $form->get('montantC')->addError(new FormError('Insufficient balance. Please enter a lower amount.'));
            } else {
            $entityManager->flush();

            return $this->redirectToRoute('cheque_list1', ['ref' => $c->getId()]);}
        }

        return $this->renderForm('cheque/front/editCh.html.twig', [
            'cheque' => $form,
        ]);
    }
       /**
     * @Route("/enc1/{ref}", name="rec_edit1")
     */
    public function edit111(Request $request,$ref,ManagerRegistry $managerRegistry): Response                 //front
    {
        $entityManager= $managerRegistry->getManager();
        $Rec = $entityManager->getRepository(Reclamtion::class)->find($ref);
        $ch =$Rec->getChequeId();
        $c=$ch->getCompteId();

      
        
        $form = $this->createForm(Rec1Type::class, $Rec);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
         
            $entityManager->flush();

          
            return $this->redirectToRoute('cheque_list1', ['ref' => $c->getId()]);
           
        }

        return $this->renderForm('cheque/front/editRec.html.twig', [
            'reclamtion' => $form,
          
        ]);
    }



  











    #[Route('/removeEnc/{id}', name: 'reclamtion_remove')]
    public function deleteReclamtion($id, ReclamtionRepository $repository, ManagerRegistry $managerRegistry, SessionInterface $session)
    {
        $Reclamtion = $repository->find($id);
        $em = $managerRegistry->getManager();

        $RECTraitée = $session->get('REC1', []);
        $RECTraitée = array_filter($RECTraitée, function ($rec) use ($id) {
            return $rec->getId() !== $id;
        });
        $session->set('REC1', $RECTraitée);

        $em->remove($Reclamtion);
        $em->flush();
        return $this->redirectToRoute("cheque_list");
    }
    #[Route('/removeCheque/{id}', name: 'cheque_remove')]
    public function deleteCheque($id, ChequeRepository $repository, ManagerRegistry $managerRegistry, SessionInterface $session)
    {
        $Cheque = $repository->find($id);
        $em = $managerRegistry->getManager();

        $REC1 = $session->get('REC1', []);

        // Filter out reclamations associated with the deleted cheque
        $REC1 = array_filter($REC1, function ($rec) use ($id) {
            return $rec->getChequeID() && $rec->getChequeID()->getId() !== $id;
        });

        // Set the updated notification list in the session
        $session->set('REC1', $REC1);

        $em->remove($Cheque);
        $em->flush();
        return $this->redirectToRoute("cheque_list");
    }
    #[Route('/removeEnc1/{id}', name: 'Reclamtion_remove1')] //front
    public function deleteReclamtion1($id, ReclamtionRepository $repository, ManagerRegistry $managerRegistry, SessionInterface $session)
    {
        $entityManager = $managerRegistry->getManager();
        $Reclamtion = $repository->find($id);
        $Cheque = $Reclamtion->getChequeId();
        $c = $Cheque->getCompteId();

        $RECTraitée = $session->get('RECTraitée', []);
        $RECTraitée = array_filter($RECTraitée, function ($rec) use ($id) {
            return $rec->getId() !== $id;
        });
        $session->set('RECTraitée', $RECTraitée);


        $entityManager->remove($Reclamtion);
        $entityManager->flush();

        // Remove $Reclamtion from RECTraitée

        return $this->redirectToRoute('cheque_list1', ['ref' => $c->getId()]);
    }


    #[Route('/removeCheque1/{id}', name: 'cheque_remove1')] //front
    public function deleteCheque1($id, ChequeRepository $repository, ManagerRegistry $managerRegistry, SessionInterface $session)
    {
        $cheque = $repository->find($id);
        $em = $managerRegistry->getManager();
        $c = $cheque->getCompteId();

        // Get the reclamations associated with the deleted cheque from the session
        $RECTraitée = $session->get('RECTraitée', []);

        // Filter out reclamations associated with the deleted cheque
        $RECTraitée = array_filter($RECTraitée, function ($rec) use ($id) {
            return $rec->getChequeID() && $rec->getChequeID()->getId() !== $id;
        });

        // Set the updated notification list in the session
        $session->set('RECTraitée', $RECTraitée);

        // Remove the cheque from the database
        $em->remove($cheque);
        $em->flush();

        // Redirect to the appropriate route
        return $this->redirectToRoute('cheque_list1', ['ref' => $c->getId()]);
    }




    /**
     * @Route("/pdf/{ref}", name="generate_pdf")
     */
    public function generatePdf($ref): Response
    {
        // Fetch all cheque data
        $cheques = $this->getDoctrine()->getRepository(Cheque::class)->findByCompteID($ref);
        $Reclamtions = $this->getDoctrine()->getRepository(Reclamtion::class)->findAll();

        // Create a new Dompdf instance
        $dompdf = new Dompdf();

        // Load HTML content into Dompdf
        $html = $this->renderView('cheque/front/pdf.html.twig', [
            "tabcheque" => $cheques,
            "tab" => $Reclamtions,
        ]);
        $dompdf->loadHtml($html);

        // Set paper size and orientation
        $dompdf->setPaper('A4', 'portrait');

        // Render the PDF
        $dompdf->render();

        // Generate PDF file name
        $filename = 'cheques.pdf';

        // Stream PDF to the browser
        return new Response(
            $dompdf->output(),
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $filename . '"',
            ]
        );
    }




    // Generate a random number










}
