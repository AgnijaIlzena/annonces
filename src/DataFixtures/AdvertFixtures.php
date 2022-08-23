<?php

namespace App\DataFixtures;

use App\Entity\Advert;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use DateTimeImmutable;
use Faker;

class AdvertFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [
            UserFixtures::class
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        for($i = 0; $i < 20; $i++) {

            $date = new DateTimeImmutable();
            $randDate = $date->modify('-12 days');

            $advert = new Advert();
            $advert->setTitle($faker->realText(10, 1));
            $advert->setText($faker->realText(200, 1));
            $advert->setCover("https://picsum.photos/300/200");
            $advert->setPhone($faker->phoneNumber);
            $advert->setPostCode('9622'.$i);
            $advert->setCreatedAt($randDate);
            $advert->setUser($this->getReference("user_".rand(1, 9)));
            $manager->persist($advert);
        }
        $manager->flush();
    }
}
