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

    public function findAllUnder1Month(): array
    {
        $qb = $this->createQueryBuilder('s');
        $qb->where('s.dateHeureDebut > :dateLimite');

        $qb->setParameter('dateLimite', new \DateTime('-1 month'));

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
        ->leftJoin('s.participants','p')
        ->addSelect('p')
        ->where('s.dateHeureDebut > :dateLimite')
        ->setParameter('dateLimite', new \DateTime('-1 month'));



        if (!empty($filtre->getNom())) {
            $qb = $qb->andWhere('s.nom LIKE :nom')
                ->setParameter('nom', '%'.$filtre->getNom().'%');
        }

        if (!empty($filtre->getSite()) && isInstanceOf(Site::class) ) {
            $qb = $qb->andWhere('s.site = :idSite')
                ->setParameter('idSite', $filtre->getSite()->getId());
        }

        if (!empty($filtre->getDateDebut())){
            $qb = $qb->andWhere('s.dateHeureDebut > :dateDebut')
                ->setParameter('dateDebut', $filtre->getDateDebut());
        }

        if (!empty($filtre->getDateFin())){
            $qb = $qb->andWhere('s.dateLimiteInscription < :dateFin')
                ->setParameter('dateFin', $filtre->getDateFin());
        }

        if ($filtre->isSortieOuJeNeParticipePas()){
            $qb = $qb->andWhere('p.id <> :userId')
                ->setParameter('userId', $user->getId());
        }

        if ($filtre->isSortiePassee()){
            $qb = $qb->andWhere('s.dateHeureDebut < :today')
                ->setParameter('today', new \DateTime('now'));
        }

        if ($filtre->isSortieOuJeParcitipe()){
            $qb = $qb->andWhere('p.id = :userId')
                ->setParameter('userId', $user->getId());
        }

        if ($filtre->isSortieQueJOrganise()){
            $qb = $qb->andWhere('s.organisateur = :userId')
                ->setParameter('userId', $user->getId());
        }

        $querry= $qb->getQuery();
        return $querry->execute();
    }
}
