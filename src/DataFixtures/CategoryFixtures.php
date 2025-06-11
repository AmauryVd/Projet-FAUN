<?php
// src/DataFixtures/CategoryFixtures.php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
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
            $manager->persist($category);
        }

        $manager->flush();
    }
}
