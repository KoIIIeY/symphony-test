<?php

namespace App\Repository;

use App\Entity\Imported;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Imported>
 *
 * @method Imported|null find($id, $lockMode = null, $lockVersion = null)
 * @method Imported|null findOneBy(array $criteria, array $orderBy = null)
 * @method Imported[]    findAll()
 * @method Imported[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImportedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Imported::class);
    }

//    /**
//     * @return Imported[] Returns an array of Imported objects
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

//    public function findOneBySomeField($value): ?Imported
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
