<?php

namespace App\DataFixtures;

use App\Entity\Lieu;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LieuFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $bruz = $this->getReference('ville-bruz');
        $chartre = $this->getReference('ville-chartres-de-bretagne');
        $rennes = $this->getReference('ville-rennes');

        $lieu1 = new Lieu();
        $lieu1->setVille($rennes);
        $lieu1->setNom('Bar Des Sports');
        $lieu1->setRue('Quai Lamennais');
        $lieu1->setLatitude(48.109780152667724);
        $lieu1->setLongitude(-1.6805349155944138);
        $manager->persist($lieu1);
        $this->addReference('lieu-bar-des-sports',$lieu1);

        $lieu2 = new Lieu();
        $lieu2->setVille($rennes);
        $lieu2->setNom('Blizz');
        $lieu2->setRue('8 Avenue des Gayeulles');
        $lieu2->setLatitude(48.133060455322266);
        $lieu2->setLongitude(-1.652065634727478);
        $manager->persist($lieu2);
        $this->addReference('lieu-blizz',$lieu2);

        $lieu3 = new Lieu();
        $lieu3->setVille($bruz);
        $lieu3->setNom('Golf Cicé-Blossac');
        $lieu3->setRue('Avenue de la Chaise');
        $lieu3->setLatitude(48.031008);
        $lieu3->setLongitude(-1.7597746);
        $manager->persist($lieu3);
        $this->addReference('lieu-golf',$lieu3);

        $lieu4 = new Lieu();
        $lieu4->setVille($chartre);
        $lieu4->setNom('Parc des Loisirs');
        $lieu4->setRue('6 Allée Paul Cézanne');
        $lieu4->setLatitude(48.031008);
        $lieu4->setLongitude(-1.7597746);
        $manager->persist($lieu4);
        $this->addReference('lieu-parc-des-loisirs',$lieu4);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [VilleFixtures::class];

    }
}
