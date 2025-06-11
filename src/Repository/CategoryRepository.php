<?php
// src/Repository/CategoryRepository.php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    /**
     * Trouve toutes les catégories ordonnées par nom
     */
    public function findAllOrderedByName(): array
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les catégories utilisées dans les transactions
     */
    public function findCategoriesWithTransactions(): array
    {
        return $this->createQueryBuilder('c')
            ->innerJoin('c.transactions', 't')
            ->groupBy('c.id')
            ->orderBy('c.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
