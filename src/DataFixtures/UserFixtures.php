<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    private $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher=$hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $siteRennes = $this->getReference('site-rennes');
        $siteNantes = $this->getReference('site-nantes');

        $user1 = new User();
        $user1->setNom('Oie');
        $user1->setPrenom('Alan');
        $user1->setEmail('alan.oie@fakemail.com');
        $user1->setPassword($this->hasher->hashPassword( $user1,'Pa$$w0rd'));
        $user1->setTelephone('0697580026');
        $user1->setSite($siteRennes);
        $user1->setPhoto('photo_de_base.webp');
        $manager->persist($user1);
        $this->addReference('user-alan',$user1);


        $user2 = new User();
        $user2->setNom('Raves');
        $user2->setPrenom('Elisabeth');
        $user2->setEmail('elisabeth.rave@gmail.com');
        $user2->setPassword($this->hasher->hashPassword($user2 ,'123'));
        $user2->setTelephone('0736845517');
        $user2->setSite($siteNantes);
        $user2->setPhoto('photo_de_base.webp');
        $manager->persist($user2);
        $this->addReference('user-elisabeth',$user2);

        $user3 = new User();
        $user3->setNom('Im');
        $user3->setPrenom('Admin');
        $user3->setEmail('admin.olivier.yval.bryan@gmail.com');
        $user3->setPassword($this->hasher->hashPassword($user3 ,'123'));
        $user3->setTelephone('0697825403');
        $user3->setRoles(['ROLE_ADMIN']);
        $user3->setSite($siteRennes);
        $user3->setIsAdministrateur(true);
        $user3->setPhoto('photo_de_base.webp');
        $manager->persist($user3);
        $this->addReference('user-admin',$user3);

        $user4 = new User();
        $user4->setNom('Rhoïde');
        $user4->setPrenom('Paula');
        $user4->setEmail('paula.rhoide@fakemail.com');
        $user4->setPassword($this->hasher->hashPassword($user4,'Pa$$w0rd'));
        $user4->setTelephone('0684628166');
        $user4->setSite($siteRennes);
        $user4->setPhoto('photo_de_base.webp');
        $manager->persist($user4);
        $this->addReference('user-paula',$user4);

        $user5 = new User();
        $user5->setNom('Wouhin');
        $user5->setPrenom('Serge');
        $user5->setEmail('serge.wouhin@gmail.com');
        $user5->setPassword($this->hasher->hashPassword( $user5,'123'));
        $user5->setTelephone('0697815562');
        $user5->setSite($siteRennes);
        $user5->setPhoto('photo_de_base.webp');
        $manager->persist($user5);
        $this->addReference('user-serge',$user5);

        $user6 = new User();
        $user6->setNom('Pas');
        $user6->setPrenom('Visible');
        $user6->setEmail('pas.visible@gmail.com');
        $user6->setPassword($this->hasher->hashPassword( $user6,'123'));
        $user6->setTelephone('0697815562');
        $user6->setSite($siteRennes);
        $user6->setIsActif(false);
        $user6->setPhoto('photo_de_base.webp');
        $manager->persist($user6);
        $this->addReference('user-non-visible',$user6);


        //à ajouter pour inscrire lors des sorties

        $manager->flush();
    }

    public function getDependencies()
    {
        return [SiteFixtures::class];
    }
}
