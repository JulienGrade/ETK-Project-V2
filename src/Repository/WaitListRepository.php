<?php

namespace App\Repository;

use App\Entity\WaitList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method WaitList|null find($id, $lockMode = null, $lockVersion = null)
 * @method WaitList|null findOneBy(array $criteria, array $orderBy = null)
 * @method WaitList[]    findAll()
 * @method WaitList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WaitListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WaitList::class);
    }

    // /**
    //  * @return WaitList[] Returns an array of WaitList objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?WaitList
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
