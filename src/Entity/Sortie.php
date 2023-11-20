<?php

    namespace App\Entity;

    use App\Repository\SortieRepository;
    use Doctrine\Common\Collections\ArrayCollection;
    use Doctrine\Common\Collections\Collection;
    use Doctrine\DBAL\Types\Types;
    use Doctrine\ORM\Mapping as ORM;
    use Symfony\Component\Validator\Constraints as Assert;

    #[ORM\Entity(repositoryClass: SortieRepository::class)]
    class Sortie
    {
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column]
        private ?int $id = null;

        #[ORM\Column(length: 255)]
        #[Assert\NotBlank(message: 'Le nom de la sortie ne peut pas être vide.')]
        private ?string $nom = null;

        #[ORM\Column(type: Types::DATETIME_MUTABLE)]
        #[Assert\GreaterThan('today', message: 'La date de la sortie doit être ultérieure à aujourd\'hui.')]
        private ?\DateTimeInterface $dateHeureDebut = null;

        #[ORM\Column]
        private ?int $duree = null;

        #[Assert\NotBlank(message: 'La date limite d\'inscription ne peut pas être vide.')]
        #[Assert\GreaterThan('today', message: 'La date limite d\'inscription doit être ultérieure à aujourd\'hui.')]
        #[Assert\LessThan(propertyPath: "dateHeureDebut", message: 'La date limite d\'inscription doit être antérieure à la date de début.')]
        #[ORM\Column(type: Types::DATETIME_MUTABLE)]
        private ?\DateTimeInterface $dateLimiteInscription = null;

        #[ORM\Column]
        #[Assert\NotBlank(message: "le nombre d'inscription ne peut pas être vide.")]

        private ?int $nbInscriptionMax = null;

        #[ORM\Column(length: 255)]
        private ?string $infosSortie = null;

        #[ORM\ManyToOne(inversedBy: 'sorties')]
        #[ORM\JoinColumn(nullable: false)]
        private ?Site $site = null;

        #[ORM\ManyToOne(inversedBy: 'sorties')]
        #[ORM\JoinColumn(nullable: false)]
        private ?Etat $etat = null;

        #[ORM\ManyToOne(inversedBy: 'sorties')]
        #[ORM\JoinColumn(nullable: false)]
        #[Assert\NotBlank(message: 'Veuillez choisir un lieu dans la liste ou en ajouter un')]
        private ?Lieu $lieu = null;

        #[ORM\Column]
        private ?bool $isPublished = false;

        #[ORM\ManyToOne(inversedBy: 'sortiesOrganises')]
        #[ORM\JoinColumn(nullable: false)]
        private ?User $organisateur = null;

        #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'sorties')]
        private Collection $participants;

        #[ORM\Column]
        private ?bool $isArchive = false;

        #[ORM\Column(length: 255, nullable: true)]
        private ?string $motifAnnulation = null;

        public function __construct()
        {
            $this->participants = new ArrayCollection();
        }

        public function getId(): ?int
        {
            return $this->id;
        }

        public function getNom(): ?string
        {
            return $this->nom;
        }

        public function setNom(string $nom): static
        {
            $this->nom = $nom;

            return $this;
        }

        public function getDateHeureDebut(): ?\DateTimeInterface
        {
            return $this->dateHeureDebut;
        }

        public function setDateHeureDebut(\DateTimeInterface $dateHeureDebut): static
        {
            $this->dateHeureDebut = $dateHeureDebut;

            return $this;
        }

        public function getDuree(): ?int
        {
            return $this->duree;
        }

        public function setDuree(int $duree): static
        {
            $this->duree = $duree;

            return $this;
        }

        public function getDateLimiteInscription(): ?\DateTimeInterface
        {
            return $this->dateLimiteInscription;
        }

        public function setDateLimiteInscription(\DateTimeInterface $dateLimiteInscription): static
        {
            $this->dateLimiteInscription = $dateLimiteInscription;

            return $this;
        }

        public function getNbInscriptionMax(): ?int
        {
            return $this->nbInscriptionMax;
        }

        public function setNbInscriptionMax(int $nbInscriptionMax): static
        {
            $this->nbInscriptionMax = $nbInscriptionMax;

            return $this;
        }

        public function getInfosSortie(): ?string
        {
            return $this->infosSortie;
        }

        public function setInfosSortie(string $infosSortie): static
        {
            $this->infosSortie = $infosSortie;

            return $this;
        }

        public function getSite(): ?Site
        {
            return $this->site;
        }

        public function setSite(?Site $site): static
        {
            $this->site = $site;

            return $this;
        }

        public function getEtat(): ?Etat
        {
            return $this->etat;
        }

        public function setEtat(?Etat $etat): static
        {
            $this->etat = $etat;

            return $this;
        }

        public function getLieu(): ?Lieu
        {
            return $this->lieu;
        }

        public function setLieu(?Lieu $lieu): static
        {
            $this->lieu = $lieu;

            return $this;
        }

        public function isIsPublished(): ?bool
        {
            return $this->isPublished;
        }

        public function setIsPublished(bool $isPublished): static
        {
            $this->isPublished = $isPublished;

            return $this;
        }

        public function getOrganisateur(): ?User
        {
            return $this->organisateur;
        }

        public function setOrganisateur(?User $organisateur): static
        {
            $this->organisateur = $organisateur;

            return $this;
        }

        /**
         * @return Collection<int, User>
         */
        public function getParticipants(): Collection
        {
            return $this->participants;
        }

        public function addParticipant(User $participant): static
        {
            if (!$this->participants->contains($participant)) {
                $this->participants->add($participant);
            }

            return $this;
        }

        public function removeParticipant(User $participant): static
        {
            $this->participants->removeElement($participant);

            return $this;
        }

        public function isIsArchive(): ?bool
        {
            return $this->isArchive;
        }

        public function setIsArchive(bool $isArchive): static
        {
            $this->isArchive = $isArchive;

            return $this;
        }

        public function getMotifAnnulation(): ?string
        {
            return $this->motifAnnulation;
        }

        public function setMotifAnnulation(?string $motifAnnulation): static
        {
            $this->motifAnnulation = $motifAnnulation;

            return $this;
        }
    }
