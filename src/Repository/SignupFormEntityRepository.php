<?php

namespace App\Repository;

use App\Entity\SignupFormEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method SignupFormEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method SignupFormEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method SignupFormEntity[]    findAll()
 * @method SignupFormEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SignupFormEntityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SignupFormEntity::class);
    }

    // /**
    //  * @return SignupFormEntity[] Returns an array of SignupFormEntity objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SignupFormEntity
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
