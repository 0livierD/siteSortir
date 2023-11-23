<?php

namespace App\Repository;

use App\Entity\Filtre;
use App\Entity\Site;
use App\Entity\Sortie;
use App\Entity\User;
use App\Form\FiltreType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\FormInterface;
use function mysql_xdevapi\getSession;
use function PHPUnit\Framework\isEmpty;
use function PHPUnit\Framework\isInstanceOf;

/**
 * @extends ServiceEntityRepository<Sortie>
 *
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {

        parent::__construct($registry, Sortie::class);
    }

//    /**
//     * @return Sortie[] Returns an array of Sortie objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

    public function findAllUnder1Month(User $user, $filters = null): array
    {
        $qb = $this->createQueryBuilder('s');
        $qb->andWhere('s.isPublished = 1')
            ->orWhere('s.organisateur = :user')
            ->setParameter('user', $user)
            ->andWhere('s.isArchive = 0');

        if ($filters != null){
            $qb->andWhere('s.nom LIKE :nom')
                ->setParameter('nom', "%".$filters["nom"]."%");
        }



        $query = $qb->getQuery();
        return $query->execute();
    }

//    public function findOneBySomeField($value): ?Sortie
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }


    public function findSearch(User $user, array $filtres = null): array
    {

        $qb = $this->createQueryBuilder('s')
                ->andWhere('s.isPublished = 1')
                ->orWhere('s.organisateur = :user')
                ->setParameter('user', $user)
                ->andWhere('s.isArchive = 0');


        if (!empty($filtres["nom"])) {
            $qb = $qb->andWhere('s.nom LIKE :nom')
                ->setParameter('nom', '%' . $filtres["nom"] . '%');
        }

        if (!empty($filtres["site"])) {
            $qb = $qb->andWhere('s.site =:idSite')
                ->setParameter('idSite', $filtres["site"]);
        }

        if (!empty($filtres["dateDebut"])) {
            $qb = $qb->andWhere('s.dateHeureDebut > :dateDebut')
                ->setParameter('dateDebut', $filtres["dateDebut"]);
        }

        if (!empty($filtres["dateFin"])) {
            $qb = $qb->andWhere('s.dateLimiteInscription < :dateFin')
                ->setParameter('dateFin', $filtres["dateFin"]);
        }


        if (!empty($filtres["sortieOuJeParcitipe"]) && !empty($filtres["sortieOuJeNeParticipePas"])) {


            $qb = $qb->orWhere(':userId NOT MEMBER OF s.participants')
                ->setParameter('userId', $user->getId())
                ->orWhere(':userId MEMBER OF s.participants')
                ->setParameter('userId', $user->getId())
                ->andWhere('s.isArchive = 0');


        } else {
            if (!empty($filtres["sortieOuJeNeParticipePas"])) {
                $qb = $qb->andWhere(':userId NOT MEMBER OF s.participants')
                    ->setParameter('userId', $user->getId());
            }

            if (!empty($filtres["sortieOuJeParcitipe"])) {

                $qb = $qb->andWhere(':userId MEMBER OF s.participants')
                    ->setParameter('userId', $user->getId());
            }

        }

        if (!empty($filtres["sortiePassee"])) {
            $qb = $qb->andWhere('s.dateHeureDebut < :today')
                ->setParameter('today', new \DateTime('now'));
        }


        if (!empty($filtres["sortieQueJOrganise"])) {
            $qb = $qb->andWhere('s.organisateur = :userId')
                ->setParameter('userId', $user->getId());
        }

        $querry = $qb->getQuery();
        return $querry->execute();
    }

    public function findUneSortieAvecParticipant(Sortie $sortie)
    {
        $qb = $this->createQueryBuilder('s')
            ->join('s.participants','p')
            ->andWhere('s.id =:idSortie')
            ->setParameter('idSortie',$sortie->getId());

        $querry = $qb->getQuery();
        return $querry->execute();
    }
}


