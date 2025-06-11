<?php

namespace App\Controller;

use App\Entity\Transaction;
use App\Repository\TransactionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/transactions')]
class TransactionController extends AbstractController
{
    #[Route('/', name: 'app_transactions_index', methods: ['GET'])]
    public function index(TransactionRepository $transactionRepository): Response
    {
        $transactions = $transactionRepository->findBy([], ['date' => 'DESC']);
        
        $totalIncome = $transactionRepository->getTotalByType('income');
        $totalExpense = $transactionRepository->getTotalByType('expense');
        $balance = $totalIncome - $totalExpense;

        return $this->render('transaction/index.html.twig', [
            'transactions' => $transactions,
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'balance' => $balance,
        ]);
    }
} 