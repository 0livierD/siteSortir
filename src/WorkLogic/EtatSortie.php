<?php

namespace App\WorkLogic;

use App\Entity\Sortie;
use App\Repository\EtatRepository;

class EtatSortie
{

    public function miseAJourEtatDeSortie(EtatRepository $etatRepository,Sortie $sortie): Sortie
    {
        // récupération des états
        $etats = $etatRepository->findAll();



        //verification si complet
        if ($sortie->getParticipants() === $sortie->getNbInscriptionMax()){
            $sortie->setNom('Youpi');
        }
        return $sortie;
    }
}