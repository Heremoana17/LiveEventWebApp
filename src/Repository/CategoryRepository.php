<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\AST\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Category>
 *
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

//    /**
//     * @return Category[] Returns an array of Category objects
//     */
   public function findByCategory($value): array
   {
       return $this->createQueryBuilder('c')
           ->join(Event::class, 'e')
           ->Where('c.parent = :val')
           ->setParameter('val', $value)
           ->getQuery()
           ->getResult()
       ;
   }
   public function test($value): array
   {
       return $this->createQueryBuilder('c')
            ->join('c.events', 'e')
            ->andWhere('e.category = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'DESC')
            ->getQuery()
            ->getResult()
       ;
   }

//    public function findOneBySomeField($value): ?Category
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
