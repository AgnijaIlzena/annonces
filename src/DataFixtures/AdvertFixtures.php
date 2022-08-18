<?php

namespace App\DataFixtures;

use App\Entity\Advert;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use DateTimeImmutable;

class AdvertFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for($i = 0; $i < 10; $i++) {

            $date = new DateTimeImmutable();
            $randDate = $date->modify('-12 days');

            $advert = new Advert();
            $advert->setTitle("Title_$i");
            $advert->setText("Text_s simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum. $i");
            $advert->setCover("https://picsum.photos/300/200");
            $advert->setPhone($i);
            $advert->setPostCode("$i.0000");
            $advert->setCreatedAt($randDate);
            $manager->persist($advert);
        }
        $manager->flush();
    }
}
