<?php

namespace App\DataFixtures;

use App\Entity\Livre;
use Faker\Factory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class Livresfixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        for ($i =1; $i<=20; $i++){

            $livre = new Livre();
            $titre=$faker->name();
            $livre->setTitre($titre);
            $livre->setSlug(strtolower(str_replace(' ', '-', $titre)));
            $livre->setImage('https://picsum.photos/200?id='.$i);
            $livre->setResume($faker->text);
            $livre->setEditeur($faker->name()); 
            $livre->setIsbn($faker->isbn13());
            $livre->setDateEdition($faker->dateTimeBetween('-6 months'));
            $livre->setPrix($faker->randomFloat(2, 10, 700));  
            $livre->setDisponible($faker->numberBetween(0, 1));
            $manager->persist($livre);
    
           }  
        $manager->flush();
    }
}
