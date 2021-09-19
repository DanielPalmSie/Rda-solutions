<?php

namespace App\Repository;

use App\Entity\TransactionHistoryLogs;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method TransactionHistoryLogs|null find($id, $lockMode = null, $lockVersion = null)
 * @method TransactionHistoryLogs|null findOneBy(array $criteria, array $orderBy = null)
 * @method TransactionHistoryLogs[]    findAll()
 * @method TransactionHistoryLogs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransactionHistoryLogsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TransactionHistoryLogs::class);
    }


    public function getTransactionsByUser(User $user)
    {
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('t')
            ->from($this->getEntityName(), 't')
            ->where('t.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return TransactionHistoryLogs[] Returns an array of TransactionHistoryLogs objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TransactionHistoryLogs
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
