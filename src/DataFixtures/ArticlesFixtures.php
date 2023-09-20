<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class ArticlesFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

    }
}
