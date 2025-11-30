<?php

namespace App\Controller;

use App\Entity\Transaction;
use App\Form\TransactionType;
use App\Repository\TransactionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Form\FormError;
use Knp\Component\Pager\PaginatorInterface;

use Dompdf\Dompdf;



#[Route('/transaction')]
class TransactionController extends AbstractController
{
    #[Route('/', name: 'app_transaction_index', methods: ['GET'])]
    public function index(Request $request,TransactionRepository $transactionRepository, PaginatorInterface $paginator): Response
    {
        $queryBuilder = $transactionRepository->createQueryBuilder('t');

        $pagination = $paginator->paginate(
            $queryBuilder->getQuery(), // Doctrine Query object
            $request->query->getInt('page', 1), // Current page number, default is 1
            3 // Number of items per page
        );

        return $this->render('transaction/index.html.twig', [
            'transactions' => $transactionRepository->findAll(),
            'pagination' => $pagination,
        ]);
    }

    #[Route('/indexback', name: 'app_transaction_indexback', methods: ['GET'])]
    public function indexback(Request $request,TransactionRepository $transactionRepository, PaginatorInterface $paginator): Response
    {
        $queryBuilder = $transactionRepository->createQueryBuilder('t');

        $pagination = $paginator->paginate(
            $queryBuilder->getQuery(), // Doctrine Query object
            $request->query->getInt('page', 1), // Current page number, default is 1
            3 // Number of items per page
        );

        return $this->render('transaction/indexback.html.twig', [
            'transactions' => $transactionRepository->findAll(),
            'pagination' => $pagination,
        ]);
    }

    #[Route('/new', name: 'app_transaction_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,ValidatorInterface $validator): Response
    {
        $transaction = new Transaction();
        $form = $this->createForm(TransactionType::class, $transaction);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'success');
            $entityManager->persist($transaction);
            $entityManager->flush();

            return $this->redirectToRoute('app_transaction_index', [], Response::HTTP_SEE_OTHER);
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


        return $this->render('transaction/new.html.twig', [
            'transaction' => $transaction,
            'form' => $form->createView(),
            'invalidFields' => $invalidFields,
        ]);
    }

    #[Route('/{id}', name: 'app_transaction_show', methods: ['GET'])]
    public function show(Transaction $transaction): Response
    {
        return $this->render('transaction/show.html.twig', [
            'transaction' => $transaction,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_transaction_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Transaction $transaction, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TransactionType::class, $transaction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_transaction_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('transaction/edit.html.twig', [
            'transaction' => $transaction,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_transaction_delete', methods: ['POST'])]
    public function delete(Request $request, Transaction $transaction, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$transaction->getId(), $request->request->get('_token'))) {
            $entityManager->remove($transaction);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_transaction_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/transaction/generate-pdf', name: 'generate_transaction_pdf', methods: ['GET'])]
public function generateTransactionPdf(TransactionRepository $transactionRepository): Response
{
    // Fetch transactions
    $transactions = $transactionRepository->findAll();

    // Render Twig template to HTML
    $html = $this->renderView('transaction/pdftransaction.html.twig', [
        'transactions' => $transactions,
    ]);

    // Generate PDF
    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    // Return PDF as response
    return new Response($dompdf->output(), Response::HTTP_OK, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="transactions.pdf"',
    ]);
}

    
}
