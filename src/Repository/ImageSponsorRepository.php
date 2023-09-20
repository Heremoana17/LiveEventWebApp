<?php

namespace App\Repository;

use App\Entity\ImageSponsor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ImageSponsor>
 *
 * @method ImageSponsor|null find($id, $lockMode = null, $lockVersion = null)
 * @method ImageSponsor|null findOneBy(array $criteria, array $orderBy = null)
 * @method ImageSponsor[]    findAll()
 * @method ImageSponsor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImageSponsorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ImageSponsor::class);
    }

//    /**
//     * @return ImageSponsor[] Returns an array of ImageSponsor objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ImageSponsor
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
