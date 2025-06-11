<?php
// src/Repository/TransactionRepository.php

namespace App\Repository;

use App\Entity\Transaction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TransactionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Transaction::class);
    }

    public function findByMonth(\DateTime $date): array
    {
        $startDate = clone $date;
        $startDate->modify('first day of this month')->setTime(0, 0, 0);

        $endDate = clone $date;
        $endDate->modify('last day of this month')->setTime(23, 59, 59);

        return $this->createQueryBuilder('t')
            ->andWhere('t.date BETWEEN :start AND :end')
            ->setParameter('start', $startDate)
            ->setParameter('end', $endDate)
            ->orderBy('t.date', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function getTotalByType(string $type, \DateTime $date = null): float
    {
        $qb = $this->createQueryBuilder('t')
            ->select('SUM(t.amount)')
            ->andWhere('t.type = :type')
            ->setParameter('type', $type);

        if ($date) {
            $startDate = clone $date;
            $startDate->modify('first day of this month')->setTime(0, 0, 0);

            $endDate = clone $date;
            $endDate->modify('last day of this month')->setTime(23, 59, 59);

            $qb->andWhere('t.date BETWEEN :start AND :end')
                ->setParameter('start', $startDate)
                ->setParameter('end', $endDate);
        }

        $result = $qb->getQuery()->getSingleScalarResult();
        return $result ? (float) $result : 0.0;
    }

    public function getExpensesByCategory(\DateTime $date = null): array
    {
        $qb = $this->createQueryBuilder('t')
            ->select('c.name as category, c.color, SUM(t.amount) as total')
            ->join('t.category', 'c')
            ->andWhere('t.type = :type')
            ->setParameter('type', 'expense')
            ->groupBy('c.id');

        if ($date) {
            $startDate = clone $date;
            $startDate->modify('first day of this month')->setTime(0, 0, 0);

            $endDate = clone $date;
            $endDate->modify('last day of this month')->setTime(23, 59, 59);

            $qb->andWhere('t.date BETWEEN :start AND :end')
                ->setParameter('start', $startDate)
                ->setParameter('end', $endDate);
        }

        return $qb->getQuery()->getResult();
    }
}
