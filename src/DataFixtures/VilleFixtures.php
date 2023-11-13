<?php

namespace App\DataFixtures;

use App\Entity\Ville;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class VilleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $ville1 = new Ville();
        $ville1->setNom("Chartres-de-Bretagne");
        $ville1->setCodePostal("35131");
        $manager->persist($ville1);
        $this->addReference('ville-chartres-de-bretagne',$ville1);

        $ville2 = new Ville();
        $ville2->setNom("Bruz");
        $ville2->setCodePostal("35170");
        $manager->persist($ville2);
        $this->addReference('ville-bruz',$ville2);

        $ville3 = new Ville();
        $ville3->setNom("Rennes");
        $ville3->setCodePostal("35000");
        $manager->persist($ville3);
        $this->addReference('ville-rennes',$ville3);

        $manager->flush();
    }
}
