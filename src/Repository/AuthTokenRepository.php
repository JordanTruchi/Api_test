<?php

namespace App\Repository;

use App\Entity\AuthToken;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AuthToken|null find($id, $lockMode = null, $lockVersion = null)
 * @method AuthToken|null findOneBy(array $criteria, array $orderBy = null)
 * @method AuthToken[]    findAll()
 * @method AuthToken[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuthTokenRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AuthToken::class);
    }
    public function deleteByToken($token)
    {
        return $this->createQueryBuilder('a')
            ->delete()
            ->andWhere('a.value = :token')
            ->setParameter('token', $token)
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return AuthToken[] Returns an array of AuthToken objects
    //  */




    /*
    public function findOneBySomeField($value): ?AuthToken
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
