<?php

namespace App\DataFixtures;

use App\Entity\Site;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SiteFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $site1 = new Site();
        $site1->setNom('Rennes');
        $manager->persist($site1);
        $this->addReference('site-rennes',$site1);

        $site2 = new Site();
        $site2->setNom('Nantes');
        $manager->persist($site2);
        $this->addReference('site-nantes',$site2);

        $manager->flush();
    }
}
