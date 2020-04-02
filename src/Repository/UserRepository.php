<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findByDiff($string)
    {
        return $this->createQueryBuilder('u')
                ->select('u')
                ->where('u.name=?1')
                ->orWhere('u.surname=?1')
                ->setParameter(1, $string)
                ->getQuery()
                ->getResult();
    }

    public function findByHalf($firstHalf, $secondHalf)
    {
        return $this->createQueryBuilder('u')
                ->select('u')
                ->where('u.name LIKE ?1')
                ->andWhere('u.surname LIKE ?2')
                ->orWhere('u.name LIKE ?2')
                ->orWhere('u.surname LIKE ?1')
                ->setParameter(1, "%$firstHalf%")
                ->setParameter(2, "%$secondHalf%")
                ->getQuery()
                ->getResult();
    }

    public function slugOrId($string)
    {
        return $this->createQueryBuilder('u')
            ->select('u')
            ->where('u.slug=?1')
            ->orWhere('u.id=?1')
            ->setParameter(1, $string)
            ->getQuery()
            ->getSingleResult();
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
