<?php

namespace App\Repository;

use App\Entity\Guard;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Guard>
 *
 * @method Guard|null find($id, $lockMode = null, $lockVersion = null)
 * @method Guard|null findOneBy(array $criteria, array $orderBy = null)
 * @method Guard[]    findAll()
 * @method Guard[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GuardRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Guard::class);
    }

//    /**
//     * @return Guard[] Returns an array of Guard objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('g.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Guard
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
