<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user1 = new User();
        $user1->setNom('Oie');
        $user1->setPrenom('Alan');
        $user1->setEmail('alan.oie@gmail.com');
        $user1->setPassword('123');
        $user1->setTelephone('0697580026');
        $manager->persist($user1);

        $user2 = new User();
        $user2->setNom('Raves');
        $user2->setPrenom('Elisabeth');
        $user2->setEmail('elisabeth.rave@gmail.com');
        $user2->setPassword('123');
        $user2->setTelephone('0736845517');
        $manager->persist($user2);

        $user3 = new User();
        $user3->setNom('Im');
        $user3->setPrenom('Admin');
        $user3->setEmail('admin@gmail.com');
        $user3->setPassword('123');
        $user3->setTelephone('0697825403');
        $manager->persist($user3);

        //à ajouter pour inscrire lors des sorties

        $manager->flush();
    }
}
