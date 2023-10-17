<?php

namespace App\Repository;

use App\Entity\Fastfood;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Fastfood>
 *
 * @method Fastfood|null find($id, $lockMode = null, $lockVersion = null)
 * @method Fastfood|null findOneBy(array $criteria, array $orderBy = null)
 * @method Fastfood[]    findAll()
 * @method Fastfood[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FastfoodRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fastfood::class);
    }

//    /**
//     * @return Fastfood[] Returns an array of Fastfood objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Fastfood
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
