<?php

namespace App\Entity;

use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\VarDumper\Cloner\Data;

class Filtre
{

    private  $site;
    /**
     * @Assert\Type("string")
     */
    private string $nom = "";
    private $dateDebut;
    private $dateFin;

    private bool $sortieQueJOrganise;
    private bool $sortieOuJeParcitipe;
    private bool $sortieOuJeNeParticipePas;
    private bool $sortiePassee;


    public function getSite()
    {

        return $this->site;


    }

    public function setSite($site): void
    {

        $this->site = $site;

    }

    public function getNom(): string
    {

        return $this->nom;

    }

    public function setNom($nom): void
    {
        if (isset($nom))
            $this->nom = $nom;
        else
            $this->nom = "";


    }

    /**
     * @return mixed
     */
    public function getDateDebut()
    {
        return $this->dateDebut;
    }

    /**
     * @param mixed $dateDebut
     */
    public function setDateDebut($dateDebut): void
    {
        $this->dateDebut = $dateDebut;
    }

    /**
     * @return mixed
     */
    public function getDateFin()
    {
        return $this->dateFin;
    }

    /**
     * @param mixed $dateFin
     */
    public function setDateFin($dateFin): void
    {
        $this->dateFin = $dateFin;
    }


    public function isSortieQueJOrganise(): bool
    {
        return $this->sortieQueJOrganise;
    }

    public function setSortieQueJOrganise(bool $sortieQueJOrganise): void
    {
        $this->sortieQueJOrganise = $sortieQueJOrganise;
    }

    public function isSortieOuJeParcitipe(): bool
    {
        return $this->sortieOuJeParcitipe;
    }

    public function setSortieOuJeParcitipe(bool $sortieOuJeParcitipe): void
    {
        $this->sortieOuJeParcitipe = $sortieOuJeParcitipe;
    }

    public function isSortieOuJeNeParticipePas(): bool
    {
        return $this->sortieOuJeNeParticipePas;
    }

    public function setSortieOuJeNeParticipePas(bool $sortieOuJeNeParticipePas): void
    {
        $this->sortieOuJeNeParticipePas = $sortieOuJeNeParticipePas;
    }

    public function isSortiePassee(): bool
    {
        return $this->sortiePassee;
    }

    public function setSortiePassee(bool $sortiePassee): void
    {
        $this->sortiePassee = $sortiePassee;
    }
}
