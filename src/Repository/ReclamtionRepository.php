<?php

namespace App\Repository;

use App\Entity\Reclamtion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reclamtion>
 *
 * @method Reclamtion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reclamtion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reclamtion[]    findAll()
 * @method Reclamtion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReclamtionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reclamtion::class);
    }

    /**
     * @return Reclamtion[] Returns an array of Reclamtion objects
    */
    public function findByChequeID($value): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.chequeID = :val')
            ->setParameter('val', $value)
            
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

//    public function findOneBySomeField($value): ?Reclamtion
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
