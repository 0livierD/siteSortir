<?php

namespace App\DataFixtures;

use App\Entity\Sortie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SortieFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        //importation des fixtures user
        $userAlan = $this->getReference('user-alan');
        $userElisabeth = $this->getReference('user-elisabeth');
        $userAdmin = $this->getReference('user-admin');
        $userPaula = $this->getReference('user-paula');
        $userSerge = $this->getReference('user-serge');
        $userNonVisible = $this->getReference('user-pas-visible');

        //importation des états
        $etatCreee = $this->getReference('etat-creee');
        $etatOuverte = $this->getReference('etat-ouverte');
        $etatCloturee = $this->getReference('etat-cloturee');
        $etatEnCours = $this->getReference('etat-en-cours');
        $etatPassee = $this->getReference('etat-passee');
        $etatAnnulee = $this->getReference('etat-annulee');

        //importation des site
        $siteRennes = $this->getReference('site-rennes');
        $siteNantes = $this->getReference('site-nantes');

        //importation des lieux
        $lieuBDS = $this->getReference('lieu-bar-des-sports');
        $lieuBlizz = $this->getReference('lieu-blizz');
        $lieuGolf = $this->getReference('lieu-golf');
        $lieuParcDesLoisirs = $this->getReference('lieu-parc-des-loisirs');


        $sortie1 = new Sortie();
        $sortie1->setSite($siteRennes);
        $sortie1->setOrganisateur($userAlan);
        $sortie1->setLieu($lieuParcDesLoisirs);
        $sortie1->setInfosSortie('Apéro pour fêter le diplôme');
        $sortie1->setDateHeureDebut(new \DateTime('08-03-2024 19:00:00'));
        $sortie1->setDateLimiteInscription(new \DateTime('05-03-2024 19:00:00'));
        $sortie1->setDuree(300);
        $sortie1->setNbInscriptionMax(17);
        $sortie1->addParticipant($userAlan);
        $sortie1->setEtat($etatCreee);
        $manager->persist($sortie1);

        $sortie2 = new Sortie();
        $sortie2->setSite($siteRennes);
        $sortie2->setOrganisateur($userAlan);
        $sortie2->setLieu($lieuParcDesLoisirs);
        $sortie2->setInfosSortie('Apéro pour fêter le diplôme');
        $sortie2->setDateHeureDebut(new \DateTime('08-03-2024 19:00:00'));
        $sortie2->setDateLimiteInscription(new \DateTime('05-03-2024 19:00:00'));
        $sortie2->setDuree(300);
        $sortie2->setNbInscriptionMax(17);
        $sortie2->setEtat($etatCreee);
        $manager->persist($sortie2);

        $sortie3 = new Sortie();
        $sortie3->setSite($siteRennes);
        $sortie3->setOrganisateur($userAlan);
        $sortie3->setLieu($lieuParcDesLoisirs);
        $sortie3->setInfosSortie('Apéro pour fêter le diplôme');
        $sortie3->setDateHeureDebut(new \DateTime('08-03-2024 19:00:00'));
        $sortie3->setDateLimiteInscription(new \DateTime('05-03-2024 19:00:00'));
        $sortie3->setDuree(300);
        $sortie3->setNbInscriptionMax(17);
        $sortie3->setEtat($etatCreee);
        $manager->persist($sortie3);

        $sortie4 = new Sortie();
        $sortie4->setSite($siteRennes);
        $sortie4->setOrganisateur($userAlan);
        $sortie4->setLieu($lieuParcDesLoisirs);
        $sortie4->setInfosSortie('Apéro pour fêter le diplôme');
        $sortie4->setDateHeureDebut(new \DateTime('08-03-2024 19:00:00'));
        $sortie4->setDateLimiteInscription(new \DateTime('05-03-2024 19:00:00'));
        $sortie4->setDuree(300);
        $sortie4->setNbInscriptionMax(17);
        $sortie4->setEtat($etatCreee);
        $manager->persist($sortie4);

        $sortie5 = new Sortie();
        $sortie5->setSite($siteRennes);
        $sortie5->setOrganisateur($userAlan);
        $sortie5->setLieu($lieuParcDesLoisirs);
        $sortie5->setInfosSortie('Apéro pour fêter le diplôme');
        $sortie5->setDateHeureDebut(new \DateTime('08-03-2024 19:00:00'));
        $sortie5->setDateLimiteInscription(new \DateTime('05-03-2024 19:00:00'));
        $sortie5->setDuree(300);
        $sortie5->setNbInscriptionMax(17);
        $sortie5->setEtat($etatCreee);
        $manager->persist($sortie5);

        $sortie6 = new Sortie();
        $sortie6->setSite($siteRennes);
        $sortie6->setOrganisateur($userAlan);
        $sortie6->setLieu($lieuParcDesLoisirs);
        $sortie6->setInfosSortie('Apéro pour fêter le diplôme');
        $sortie6->setDateHeureDebut(new \DateTime('08-03-2024 19:00:00'));
        $sortie6->setDateLimiteInscription(new \DateTime('05-03-2024 19:00:00'));
        $sortie6->setDuree(300);
        $sortie6->setNbInscriptionMax(17);
        $sortie6->setEtat($etatCreee);
        $manager->persist($sortie6);

        $sortie7 = new Sortie();
        $sortie7->setSite($siteRennes);
        $sortie7->setOrganisateur($userAlan);
        $sortie7->setLieu($lieuParcDesLoisirs);
        $sortie7->setInfosSortie('Apéro pour fêter le diplôme');
        $sortie7->setDateHeureDebut(new \DateTime('08-03-2024 19:00:00'));
        $sortie7->setDateLimiteInscription(new \DateTime('05-03-2024 19:00:00'));
        $sortie7->setDuree(300);
        $sortie7->setNbInscriptionMax(17);
        $sortie7->setEtat($etatCreee);
        $manager->persist($sortie7);

        $sortie8 = new Sortie();
        $sortie8->setSite($siteNantes);
        $sortie8->setOrganisateur($userPaula);
        $sortie8->setLieu($lieuGolf);
        $sortie8->setInfosSortie('Golf en Bretagne');
        $sortie8->setDateHeureDebut(new \DateTime('27-11-2023 14:00:00'));
        $sortie8->setDateLimiteInscription(new \DateTime('25-11-2023 19:00:00'));
        $sortie8->setDuree(200);
        $sortie8->addParticipant($userPaula)
        $sortie8->setNbInscriptionMax(1);
        $sortie8->setEtat($etatCreee);
        $manager->persist($sortie8);

        // fait user1 et 8

        $manager->flush();
    }
}
