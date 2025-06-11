<?php
// src/Controller/BudgetController.php

namespace App\Controller;

use App\Entity\Transaction;
use App\Form\TransactionType;
use App\Repository\TransactionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BudgetController extends AbstractController
{
    // Ajoutez cette route pour la page d'accueil par défaut
    #[Route('/', name: 'app_home')]
    public function home(): Response
    {
        return $this->redirectToRoute('budget_dashboard');
    }

    #[Route('/budget', name: 'budget_dashboard')]
    public function dashboard(TransactionRepository $transactionRepository): Response
    {
        $currentDate = new \DateTime();

        $monthlyIncome = $transactionRepository->getTotalByType('income', $currentDate);
        $monthlyExpenses = $transactionRepository->getTotalByType('expense', $currentDate);
        $balance = $monthlyIncome - $monthlyExpenses;

        $recentTransactions = $transactionRepository->findBy(
            [],
            ['date' => 'DESC'],
            10
        );

        $expensesByCategory = $transactionRepository->getExpensesByCategory($currentDate);

        return $this->render('budget/dashboard.html.twig', [
            'monthly_income' => $monthlyIncome,
            'monthly_expenses' => $monthlyExpenses,
            'balance' => $balance,
            'recent_transactions' => $recentTransactions,
            'expenses_by_category' => $expensesByCategory,
            'current_month' => $currentDate->format('F Y')
        ]);
    }

    #[Route('/transactions', name: 'budget_transactions')]
    public function transactions(Request $request, TransactionRepository $transactionRepository): Response
    {
        $month = $request->query->get('month', date('Y-m'));
        $date = \DateTime::createFromFormat('Y-m', $month) ?: new \DateTime();

        $transactions = $transactionRepository->findByMonth($date);

        return $this->render('budget/transactions.html.twig', [
            'transactions' => $transactions,
            'current_month' => $date,
            'selected_month' => $month
        ]);
    }

    #[Route('/transaction/new', name: 'budget_transaction_new')]
    public function newTransaction(Request $request, EntityManagerInterface $em): Response
    {
        $transaction = new Transaction();
        $form = $this->createForm(TransactionType::class, $transaction);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($transaction);
            $em->flush();

            $this->addFlash('success', 'Transaction ajoutée avec succès');
            return $this->redirectToRoute('budget_dashboard');
        }

        return $this->render('budget/transaction_form.html.twig', [
            'form' => $form,
            'title' => 'Nouvelle Transaction'
        ]);
    }

    #[Route('/transaction/{id}/edit', name: 'budget_transaction_edit')]
    public function editTransaction(Transaction $transaction, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(TransactionType::class, $transaction);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Transaction modifiée avec succès');
            return $this->redirectToRoute('budget_dashboard');
        }

        return $this->render('budget/transaction_form.html.twig', [
            'form' => $form,
            'title' => 'Modifier Transaction',
            'transaction' => $transaction
        ]);
    }

    #[Route('/transaction/{id}/delete', name: 'budget_transaction_delete', methods: ['POST'])]
    public function deleteTransaction(Transaction $transaction, EntityManagerInterface $em): Response
    {
        $em->remove($transaction);
        $em->flush();

        $this->addFlash('success', 'Transaction supprimée avec succès');
        return $this->redirectToRoute('budget_dashboard');
    }
}
