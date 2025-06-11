<?php
// src/Command/InitCategoriesCommand.php

namespace App\Command;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:init-categories',
    description: 'Initialise les catégories de base',
)]
class InitCategoriesCommand extends Command
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $categories = [
            ['name' => 'Alimentation', 'color' => '#28a745'],
            ['name' => 'Transport', 'color' => '#007bff'],
            ['name' => 'Logement', 'color' => '#ffc107'],
            ['name' => 'Loisirs', 'color' => '#dc3545'],
            ['name' => 'Santé', 'color' => '#6f42c1'],
            ['name' => 'Salaire', 'color' => '#20c997'],
            ['name' => 'Autres', 'color' => '#6c757d'],
        ];

        foreach ($categories as $categoryData) {
            $category = new Category();
            $category->setName($categoryData['name']);
            $category->setColor($categoryData['color']);
            $this->entityManager->persist($category);
        }

        $this->entityManager->flush();

        $io->success('Les catégories ont été créées avec succès !');

        return Command::SUCCESS;
    }
}
