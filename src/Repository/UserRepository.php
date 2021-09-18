<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findUsersWithConfirmationToken()
    {
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('user')
            ->from($this->getEntityName(), 'user')
            ->where('user.confirmationToken IS NOT NULL')
            ->getQuery()
            ->getResult()
            ;
    }
}