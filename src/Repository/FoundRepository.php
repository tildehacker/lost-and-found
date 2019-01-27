<?php

namespace App\Repository;

use App\Entity\Found;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Found|null find($id, $lockMode = null, $lockVersion = null)
 * @method Found|null findOneBy(array $criteria, array $orderBy = null)
 * @method Found[]    findAll()
 * @method Found[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FoundRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Found::class);
    }

    // /**
    //  * @return Found[] Returns an array of Found objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Found
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
