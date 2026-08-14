<?php

namespace App\Repository;

use App\Entity\Avis;
use BcMath\Number;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Avis>
 */
class AvisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Avis::class);
    }

    //    /**
    //     * @return Avis[] Returns an array of Avis objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Avis
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findByProduct(int $value): array
    {
        return $this->createQueryBuilder('a')
        ->select('a.id','a.contenu','a.likes','a.dislikes','u.nom','u.id as userId')
        ->leftJoin('a.user','u')
        ->andWhere('a.product = :product')
        ->setParameter('product', $value)
        ->orderBy('a.id', 'ASC')
        ->getQuery()
        ->getResult();
           ;
    }

    public function findByUserId(int $value): array
    {
        return $this->createQueryBuilder('a')
        ->select('a.id','a.contenu','a.likes','a.dislikes','u.nom','p.nom as pname','p.image','u.id as userId','p.id as productId')
        ->leftJoin('a.user','u')
        ->leftJoin('a.product','p')
        ->andWhere('a.user = :user')
        ->setParameter('user', $value)
        ->orderBy('a.id', 'ASC')
        ->getQuery()
        ->getResult();
           ;
    }

}
