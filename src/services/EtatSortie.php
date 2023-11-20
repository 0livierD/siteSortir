<?php

namespace App\services;

use App\Entity\Sortie;
use App\Repository\EtatRepository;
use Doctrine\ORM\EntityManagerInterface;

class EtatSortie
{

    public function miseAJourEtatDeSortie(EntityManagerInterface $entityManager, EtatRepository $etatRepository, Sortie $sortie): Sortie
    {
        // récupération des états
        $etats = $etatRepository->findAll();
        $etatCreee = $etats[0];
        $etatOuverte = $etats[1];
        $etatCloturee = $etats[2];
        $etatEnCours = $etats[3];
        $etatPassee = $etats[4];
        $etatAnnulee = $etats[5];


        /*$now = new \DateTime('+1 hour');*/
        $now = new \DateTime('now');



        //verification si cloture
        if ($sortie->getDateLimiteInscription() < $now) {

            $sortie->setEtat($etatCloturee);
            $entityManager->persist($sortie);
            $entityManager->flush();

        }

        //verfication si ouverte
        if ($sortie->getDateLimiteInscription() > $now) {
            $sortie->setEtat($etatOuverte);
            $entityManager->persist($sortie);
            $entityManager->flush();
        }

        //verification si passée
        if ($sortie->getDateHeureDebut() < $now) {
            $sortie->setEtat($etatPassee);
            $entityManager->persist($sortie);
            $entityManager->flush();
        }


        //vérification si en cours
        $dateDeDebutSortie = clone $sortie->getDateHeureDebut();
        $dureeActivite = new \DateInterval('PT' . $sortie->getDuree() . 'M');
        $dateHeureFinActivite = $dateDeDebutSortie->add($dureeActivite);


        if ($sortie->getDateHeureDebut() < $now == $dateHeureFinActivite > $now) {

            $sortie->setEtat($etatEnCours);
            $entityManager->persist($sortie);
            $entityManager->flush();

        }

        //vérification si créee
        if ($sortie->isIsPublished() === false) {
            $sortie->setEtat($etatCreee);
            $entityManager->persist($sortie);
            $entityManager->flush();
        }

        //vérification si annulée
        if (!empty($sortie->getMotifAnnulation())) {
            $sortie->setEtat($etatAnnulee);
            $entityManager->persist($sortie);
            $entityManager->flush();
        }

        // Archiver sortie
        if ($sortie->getDateHeureDebut()<new \DateTime('-1 month')){
            $sortie->setIsArchive(true);
            $entityManager->persist($sortie);
            $entityManager->flush();
        }





        return $sortie;
    }
}