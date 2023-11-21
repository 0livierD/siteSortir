<?php

namespace App\services;

use App\Entity\Etat;
use App\Entity\Sortie;
use App\Entity\User;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

class EtatUser
{

    public function annulerSortiesUser(User $user, EntityManagerInterface $entityManager): void
    {
        //Récupération des états pour desiscrire uniquement lors des sorties à venir
        $etatRepository = $entityManager->getRepository(Etat::class);
        $etatOuverte = $etatRepository->findOneBy(['libelle'=>'Ouverte']);
        $etatCloturee = $etatRepository->findOneBy(['libelle'=>'Clôturée']);

        $sortieRepository = $entityManager->getRepository(Sortie::class);
        $sorties = $sortieRepository->findAll();
        foreach ($sorties as $sortie) {
            if ($sortie->getOrganisateur() === $user) {
                $sortie->setIsArchive(true);
                $entityManager->persist($sortie);
                $entityManager->flush();
            }

            if ($sortie->getEtat() == $etatCloturee || $sortie->getEtat() == $etatOuverte ){
                foreach ($sortie->getParticipants() as $participant) {
                    if ($participant == $user) {
                        $sortie->removeParticipant($user);
                        $entityManager->persist($sortie);
                        $entityManager->flush();
                    }
                }
            }

        }
    }

    public function reactiverSortieUser(User $user, EntityManagerInterface $entityManager)
    {

        $sortieRepository = $entityManager->getRepository(Sortie::class);
        $sorties = $sortieRepository->findAll();
        foreach ($sorties as $sortie) {
            if ($sortie->getOrganisateur() === $user && $sortie->getDateHeureDebut()>new \DateTime('-1 month')) {
                $sortie->setIsArchive(false);
                $entityManager->persist($sortie);
                $entityManager->flush();
            }
        }
    }

}