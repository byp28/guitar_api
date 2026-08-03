<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    //    /**
    //     * @return Product[] Returns an array of Product objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    public function findByName($value): array
        {
           return $this->createQueryBuilder('p')
               ->select('p.id','p.nom','c.designation as categorie','s.designation as sousCategorie')
               ->leftJoin('p.categorie','c')
               ->leftJoin('p.sousCategorie','s')
               ->where('p.nom LIKE :val')
               ->setParameter('val', "%".$value."%")
               ->orderBy('p.id', 'ASC')
               ->setMaxResults(10)
               ->getQuery()
               ->getResult()
           ;
        }
    public function indexWithCategorieAndSubCategorie(): array
       {
           return $this->createQueryBuilder('p')
               ->select('p.id','p.nom','p.description', 'p.description_technique','p.price','p.image','p.evaluation','c.designation as categorie','s.designation as sousCategorie')
               ->leftJoin('p.categorie','c')
                ->leftJoin('p.sousCategorie','s')
               ->orderBy('p.id', 'ASC')
               ->getQuery()
               ->getResult()
           ;
       }

    //    public function findOneBySomeField($value): ?Product
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
