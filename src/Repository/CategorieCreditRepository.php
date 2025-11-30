<?php

namespace App\Repository;

use App\Entity\CategorieCredit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CategorieCredit>
 *
 * @method CategorieCredit|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategorieCredit|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategorieCredit[]    findAll()
 * @method CategorieCredit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategorieCreditRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategorieCredit::class);
    }

    public function getStatistiques()
    {
        return $this->createQueryBuilder('c')
            ->select('c.nom as categorie', 'COUNT(cr.id) as nombreCredits')
            ->leftJoin('c.credits', 'cr')
            ->groupBy('c.nom')
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return CategorieCredit[] Returns an array of CategorieCredit objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CategorieCredit
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
