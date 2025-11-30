<?php

namespace App\Controller;

use App\Entity\Carte;
use App\Form\CarteType;
use App\Repository\CarteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\CompteClient;
use Dompdf\Dompdf;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Notification; 
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Form\FormError;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Knp\Component\Pager\PaginatorInterface;



#[Route('/carte')]
class CarteController extends AbstractController
{
    #[Route('/', name: 'app_carte_index', methods: ['GET'])]
    public function index(Request $request,CarteRepository $carteRepository, PaginatorInterface $paginator): Response
    {
        $queryBuilder = $carteRepository->createQueryBuilder('c')
        ->orderBy('c.id', 'ASC');

    // Paginate the results
    $pagination = $paginator->paginate(
        $queryBuilder->getQuery(), // Doctrine Query object
        $request->query->getInt('page', 1), // Current page number, default is 1
        3 // Number of items per page
    );
        
        $visaCount = $carteRepository->countByType('visa');
        $mastercardCount = $carteRepository->countByType('mastercard');

        // Group cards by expiration date
        $calendar = [];
        $cartes = $carteRepository->findAll();
        foreach ($cartes as $carte) {
            $dateExp = $carte->getDateExp()->format('Y-m-d');
            $calendar[$dateExp][] = $carte;
        }

        // Generate calendar matrix
        $start = new \DateTime('first day of this month');
        $end = new \DateTime('last day of this month');
        $interval = new \DateInterval('P1D');
        $period = new \DatePeriod($start, $interval, $end);
        $calendarMatrix = [];
        foreach ($period as $date) {
            $day = $date->format('Y-m-d');
            $calendarMatrix[$day]['day'] = $date->format('d');
            $calendarMatrix[$day]['cards'] = isset($calendar[$day]) ? $calendar[$day] : [];
        }

        return $this->render('carte/index.html.twig', [
            'cartes' => $carteRepository->findAll(),
            'visaCount' => $visaCount,
            'mastercardCount' => $mastercardCount,
            'pagination' => $pagination,
            'calendar' => array_chunk($calendarMatrix, 7, true),
        ]);
    }

    #[Route('/new', name: 'app_carte_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,ValidatorInterface $validator): Response
    {
        $carte = new Carte();
        $form = $this->createForm(CarteType::class, $carte);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'Vous avez une nouvelle carte');
            $entityManager->persist($carte);
            $entityManager->flush();

            return $this->redirectToRoute('app_carte_index', [], Response::HTTP_SEE_OTHER);
        }

        $errors = $form->getErrors(true, false); // Récupérer les erreurs globales

$invalidFields = [];
foreach ($form->all() as $child) {
    /** @var FormError[] $fieldErrors */
    $fieldErrors = $child->getErrors(true, false);
    if (count($fieldErrors) > 0) {
        $invalidFields[$child->getName()] = true;
    }
}

        return $this->render('carte/new.html.twig', [
            'carte' => $carte,
            'form' => $form->createView(),
            'invalidFields' => $invalidFields,
        ]);
    }

    #[Route('/{id}', name: 'app_carte_show', methods: ['GET'])]
    public function show(Carte $carte): Response
    {
        return $this->render('carte/show.html.twig', [
            'carte' => $carte,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_carte_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Carte $carte, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CarteType::class, $carte);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_carte_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('carte/edit.html.twig', [
            'carte' => $carte,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_carte_delete', methods: ['POST'])]
    public function delete(Request $request, Carte $carte, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$carte->getId(), $request->request->get('_token'))) {
            $entityManager->remove($carte);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_carte_index', [], Response::HTTP_SEE_OTHER);
    }
    
    #[Route('/carte/generate-pdf', name: 'generate_pdf', methods: ['GET'])]
    public function generatePdf(CarteRepository $carteRepository): Response
{
    $cartes = $carteRepository->findAll();

    // Render the Twig template to HTML
    $html = $this->renderView('carte/pdfcarte.html.twig', [
        'cartes' => $cartes,
    ]);

    // Generate PDF
    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    // Return PDF as response
    $pdfContent = $dompdf->output();
    return new Response($pdfContent, Response::HTTP_OK, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="cartes.pdf"',
    ]);}


    
    #[Route('/search-cartes', name: 'search_cartes', methods: ['GET'])]
public function searchCartes(Request $request, CarteRepository $carteRepository): JsonResponse
{
    $searchTerm = $request->query->get('searchTerm');

    // Rechercher les cartes correspondantes au terme de recherche
    $cartes = $carteRepository->findByPartialNumC($searchTerm);

    return $this->json($cartes);
}




    private $logger; // Declare the logger property

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger; // Inject the logger service
    }

    #[Route('/send-notification/{id}', name: 'send_notification', methods: ['POST'])]
    public function sendNotification(Request $request, Carte $carte): Response
    {
        // Retrieve the associated CompteClient object
        $compteClient = $carte->getAccount();
        
        if ($compteClient !== null) {
            // Retrieve the account ID from the associated CompteClient object
            $accountId = $compteClient->getId();
            
            // Log the notification
            $this->logger->info('Notification received for account ID: ' . $accountId);
            
            // You can add code here to send the notification to the user
            // For example, send an email, push notification, etc.

            // Return a response (optional)
            return new Response('Notification received successfully', Response::HTTP_OK);
        } else {
            // Handle the case where the associated account is null (optional)
            return new Response('Associated account not found', Response::HTTP_NOT_FOUND);
        }
    }


    #[Route('/tri-prix', name: 'tri_prix')]
    public function triPrix(Request $request, CarteRepository $carteRepository): Response
    {
        $tri = $request->query->get('tri', 'asc'); // Par défaut, tri croissant
        $cartes = $carteRepository->findBy([], ['Num_C' => $tri]);

        return $this->render('carte/index.html.twig', [
            'cartes' => $cartes,
        ]);
    }
    
    #[Route('/calendar', name: 'calendar')]
    public function calendar(CarteRepository $carteRepository): Response
    {
        $cartes = $carteRepository->findAll();
    
        // Initialize an array to hold calendar data
        $calendar = [];
    
        // Group cards by expiration date
        foreach ($cartes as $carte) {
            // Get the expiration date of the card
            $expirationDate = $carte->getDateExp();
    
            // Check if expiration date is not null
            if ($expirationDate !== null) {
                // Extract month and year from the expiration date
                $expirationMonth = $expirationDate->format('F');
                $expirationYear = $expirationDate->format('Y');
    
                // Format the key for the calendar array
                $key = $expirationMonth . ' ' . $expirationYear;
    
                // Add the expiration date to the corresponding month/year in the calendar
                $calendar[$key][] = $expirationDate;
            }
        }
    
        return $this->render('carte/calendar.html.twig', [
            'calendar' => $calendar,
        ]);
    }
    

    
}
