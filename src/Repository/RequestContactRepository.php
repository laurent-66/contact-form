<?php

namespace App\Repository;

use App\Entity\RequestContact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RequestContact>
 *
 * @method RequestContact|null find($id, $lockMode = null, $lockVersion = null)
 * @method RequestContact|null findOneBy(array $criteria, array $orderBy = null)
 * @method RequestContact[]    findAll()
 * @method RequestContact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RequestContactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RequestContact::class);
    }

//    /**
//     * @return RequestContact[] Returns an array of RequestContact objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?RequestContact
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
