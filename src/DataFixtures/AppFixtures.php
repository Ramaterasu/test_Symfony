<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Users;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $user = new Users;
        $user->setUsername('Amar');
        $user->setEmail('loumi@gmail.com');
        $user->setAddress('osef');
        $user->setPhonenumber('3142424');
        $manager->persist($user);

        $manager->flush();
    }
}
