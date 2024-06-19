<?php

namespace App\DataFixtures;

use App\Entity\Serie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $this->addSeries(50, $manager);
    }

    private function addSeries(int $number, ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < $number; $i++) {

            $serie = new Serie();
            $serie
                ->setName("Serie $i")
                ->setDescription("Desription $i");

            $manager->persist($serie);
        }
        $manager->flush();

    }
}
