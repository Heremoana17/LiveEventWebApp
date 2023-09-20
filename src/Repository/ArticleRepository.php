<?php

namespace App\Repository;

use App\Entity\Article;
use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 *
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

//    /**
//     * @return Article[] Returns an array of Article objects
//     */

public function findByCategory($value): ?Article
{
    return $this->createQueryBuilder('a')
        ->join('a.categories', 'c')
        ->andWhere('c.id = :val')
        ->setParameter('val', $value)
        ->orderBy('a.id', 'DESC')
        ->getQuery()
        ->getOneOrNullResult()
    ;
}

public function pagination($value): array
{
    return $this->createQueryBuilder('a')
        ->join('a.categories', 'c')
        ->andWhere('c.id = :val')
        ->setParameter('val', $value)
        ->orderBy('a.id', 'DESC')
        ->getQuery()
        ->getResult()
    ;
}

//    public function findOneBySomeField($value): ?Article
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
