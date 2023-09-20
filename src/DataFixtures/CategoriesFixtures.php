<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoriesFixtures extends Fixture
{
    public function __construct(
        private SluggerInterface $slugger
    )
    {}

    public function load(ObjectManager $manager): void
    {
        $categoryParent = $this->createCategory('Musique', null, $manager);
        $this->createCategory('Jazz', $categoryParent, $manager);
        $this->createCategory('reggae', $categoryParent, $manager);
        $this->createCategory('Rock and roll', $categoryParent, $manager);
        $this->createCategory('Pop', $categoryParent, $manager);
        $this->createCategory('Hip-hop', $categoryParent, $manager);
        $this->createCategory('Rnb', $categoryParent, $manager);
        $this->createCategory('Electro', $categoryParent, $manager);

        $categoryParent = $this->createCategory('Evenement', null, $manager);
        $this->createCategory('Concert', $categoryParent, $manager);
        $this->createCategory('Festival', $categoryParent, $manager);
        $this->createCategory('Conference', $categoryParent, $manager);


        $manager->flush();
    }

    public function createCategory(string $name, Category $parent = null, ObjectManager $manager)
    {
        $category = new Category();
        $category->setName($name);
        $category->setSlug($this->slugger->slug($category->getName())->lower());
        $category->setParent($parent);
        $manager->persist($category);

        return $category;
    }
}
