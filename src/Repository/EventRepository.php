<?php

namespace App\Repository;

use App\Entity\Event;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Event>
 *
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

//    /**
//     * @return Event[] Returns an array of Event objects
//     */
   public function pagination($value): array
   {
       return $this->createQueryBuilder('e')
            ->join('e.category', 'c')
            ->andWhere('c.id = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'DESC')
            ->getQuery()
            ->getResult()
       ;
   }
   

   public function byDate($date): array
   {
       return $this->createQueryBuilder('e')
            ->andWhere('e.date >= :date')
            ->setParameter('date', $date)
            ->orderBy('e.id', 'DESC')
            ->getQuery()
            ->getResult()
       ;
   }
//    public function byName($name): array
//    {
//        return $this->createQueryBuilder('e')
//             ->andWhere('e.name >= :name')
//             ->setParameter('name', $name)
//             ->orderBy('e.id', 'DESC')
//             ->getQuery()
//             ->getResult()
//        ;
//    }
//    public function byCat($category): array
//    {
//        return $this->createQueryBuilder('e')
//             ->join('e.category', 'c')
//             ->andWhere('c.name = :category')
//             ->setParameter('category', $category)
//             ->getQuery()
//             ->getResult()
//        ;
//    }
   public function byNameDate($name, $date = 1672527600): array
   {
       return $this->createQueryBuilder('e')
            ->andWhere('e.name = :name', 'e.date >= :date')
            ->setParameter('name', $name)
            ->setParameter('date', $date)
            ->orderBy('e.id', 'DESC')
            ->getQuery()
            ->getResult()
       ;
   }
   public function byCityDate($city, $date = 1672527600): array
   {
       return $this->createQueryBuilder('e')
            ->andWhere('e.city = :city', 'e.date >= :date')
            ->setParameter('city', $city)
            ->setParameter('date', $date)
            ->orderBy('e.id', 'DESC')
            ->getQuery()
            ->getResult()
       ;
   }
   public function byCatDate($category, $date = 1672527600): array
   {
       return $this->createQueryBuilder('e')
            ->join('e.category', 'c')
            ->andWhere('e.date >= :date')
            ->andWhere('c.name = :category')
            ->setParameter('date', $date)
            ->setParameter('category', $category)
            ->getQuery()
            ->getResult()
       ;
   }
   public function byCatNameDate($name, $category, $date = 1672527600): array
   {
       return $this->createQueryBuilder('e')
            ->join('e.category', 'c')
            ->andWhere('e.date >= :date')
            ->andWhere('e.name = :name')
            ->andWhere('c.name = :category')
            ->setParameter('date', $date)
            ->setParameter('name', $name)
            ->setParameter('category', $category)
            ->getQuery()
            ->getResult()
       ;
   }
   public function byCatCityDate($category, $city, $date = 1672527600): array
   {
       return $this->createQueryBuilder('e')
            ->join('e.category', 'c')
            ->andWhere('e.date >= :date')
            ->andWhere('c.name = :category')
            ->andWhere('e.city = :city')
            ->setParameter('date', $date)
            ->setParameter('category', $category)
            ->setParameter('city', $city)
            ->getQuery()
            ->getResult()
       ;
   }
   public function byEventCityDate($name, $city, $date = 1672527600): array
   {
       return $this->createQueryBuilder('e')
            ->andWhere('e.date >= :date')
            ->andWhere('e.name = :name')
            ->andWhere('e.city = :city')
            ->setParameter('date', $date)
            ->setParameter('name', $name)
            ->setParameter('city', $city)
            ->getQuery()
            ->getResult()
       ;
   }
   public function byCatNameCityDate($name, $category, $city, $date = 1672527600): array
   {
       return $this->createQueryBuilder('e')
            ->join('e.category', 'c')
            ->andWhere('e.date >= :date')
            ->andWhere('e.name = :name')
            ->andWhere('c.name = :category')
            ->andWhere('e.city = :city')
            ->setParameter('city', $city)
            ->setParameter('date', $date)
            ->setParameter('name', $name)
            ->setParameter('category', $category)
            ->getQuery()
            ->getResult()
       ;
   }

//    public function findOneBySomeField($value): ?Event
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
