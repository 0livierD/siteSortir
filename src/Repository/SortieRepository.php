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

    public function findAllUnder1Month(User $user): array
    {
        $qb = $this->createQueryBuilder('s');
        $qb->andWhere('s.isPublished = 1')
        ->orWhere('s.organisateur = :user')
        ->setParameter('user', $user)
        ->andWhere('s.isArchive = 0');



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


    public function findSearch(Filtre $filtre, User $user): array
    {

        $qb = $this->createQueryBuilder('s')
                ->andWhere('s.isPublished = 1')
                ->orWhere('s.organisateur = :user')
                ->setParameter('user', $user)
                ->andWhere('s.isArchive = 0');;


        if (!empty($filtre->getNom())) {
            $qb = $qb->andWhere('s.nom LIKE :nom')
                ->setParameter('nom', '%' . $filtre->getNom() . '%');
        }

        if (!empty($filtre->getSite()) && isInstanceOf(Site::class)) {
            $qb = $qb->andWhere('s.site = :idSite')
                ->setParameter('idSite', $filtre->getSite()->getId());
        }

        if (!empty($filtre->getDateDebut())) {
            $qb = $qb->andWhere('s.dateHeureDebut > :dateDebut')
                ->setParameter('dateDebut', $filtre->getDateDebut());
        }

        if (!empty($filtre->getDateFin())) {
            $qb = $qb->andWhere('s.dateLimiteInscription < :dateFin')
                ->setParameter('dateFin', $filtre->getDateFin());
        }


        if ($filtre->isSortieOuJeParcitipe() && $filtre->isSortieOuJeNeParticipePas()) {


            $qb = $qb->orWhere(':userId NOT MEMBER OF s.participants')
                ->setParameter('userId', $user->getId())
                ->orWhere(':userId MEMBER OF s.participants')
                ->setParameter('userId', $user->getId());


        } else {
            if ($filtre->isSortieOuJeNeParticipePas()) {
                $qb = $qb->andWhere(':userId NOT MEMBER OF s.participants')
                    ->setParameter('userId', $user->getId());
            }

            if ($filtre->isSortieOuJeParcitipe()) {

                $qb = $qb->andWhere(':userId MEMBER OF s.participants')
                    ->setParameter('userId', $user->getId());
            }

        }

        if ($filtre->isSortiePassee()) {
            $qb = $qb->andWhere('s.dateHeureDebut < :today')
                ->setParameter('today', new \DateTime('now'));
        }


        if ($filtre->isSortieQueJOrganise()) {
            $qb = $qb->andWhere('s.organisateur = :userId')
                ->setParameter('userId', $user->getId());
        }

        $querry = $qb->getQuery();
        return $querry->execute();
    }
}


