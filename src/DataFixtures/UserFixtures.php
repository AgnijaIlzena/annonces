<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        for($i = 0; $i < 10; $i++) {

            $user = new User();
            $user->setEmail($faker->safeEmail);
            $user->setPassword($faker->password);
            $user->setNom($faker->lastName);
            $user->setFirstName($faker->firstName);
            $user->setPhoneNo($faker->phoneNumber);
            $this->addReference("user_$i", $user);

            $manager->persist($user);
        }
        $manager->flush();
    }
}
