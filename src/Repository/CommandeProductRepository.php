<?php

namespace App\Repository;

use App\Entity\CommandeProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CommandeProduct>
 */
class CommandeProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommandeProduct::class);
    }

    public function findAllProductByCommandeId($id): array
   {
       return $this->createQueryBuilder('c')
            ->select("c.id", "c.quantity", "p.id as productID", "p.nom", "p.price","p.image")
            ->leftJoin('c.Product_id', 'p')
           ->andWhere('c.Commande_id = :val')
           ->setParameter('val', $id)
           ->orderBy('c.id', 'ASC')
           ->getQuery()
           ->getResult()
       ;
   }

//    /**
//     * @return CommandeProduct[] Returns an array of CommandeProduct objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CommandeProduct
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
