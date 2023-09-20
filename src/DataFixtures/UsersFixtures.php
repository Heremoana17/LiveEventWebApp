<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Faker;

class UsersFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordEncoder,
        private SluggerInterface $slugger
    )
    {}
    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setEmail('57brocoli@gmail.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordEncoder->hashPassword($admin, 'admin'));
        $admin->setLastname('Perry');
        $admin->setFirstname('Here');
        $admin->setAddress('435 rue du chateau');
        $admin->setZipcode('34790');
        $admin->setCity('Grabels');
        $manager->persist($admin);

        $faker = Faker\Factory::create('fr_FR');

        for($usr=1; $usr<=20; $usr++){
            $user = new User();
            $user->setPassword($this->passwordEncoder->hashPassword($user, 'secret'));
            $user->setEmail($faker->email);
            $user->setLastname($faker->lastName);
            $user->setFirstname($faker->firstName);
            $user->setAddress($faker->streetAddress);
            $user->setZipcode(str_replace(' ', '',$faker->postcode));
            $user->setCity($faker->city);
            $manager->persist($user);
        }

        $manager->flush();
    }
}
