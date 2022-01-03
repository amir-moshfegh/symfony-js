<?php

namespace App\Repository;

use App\Entity\RepLog;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RepLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method RepLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method RepLog[]    findAll()
 * @method RepLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RepLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RepLog::class);
    }

    // /**
    //  * @return RepLog[] Returns an array of RepLog objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RepLog
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */


    /**
     * @param User|null $getUser
     * @return int|mixed|string
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findSumWeightLiftedByUser(User $getUser)
    {
        return $this->createQueryBuilder('rl')
            ->select('SUM(rl.totalWeightLifted)')
            ->andWhere('rl.author = :user')
            ->setParameter(':user', $getUser)
            ->getQuery()
            ->getSingleScalarResult();
    }


    /**
     * @return RepLog[]
     */
    public function findSumWeightLiftedAllUser()
    {
        return
            $this->createQueryBuilder('rl')
                ->select('IDENTITY(rl.author) as userId, SUM(rl.totalWeightLifted) as weight')
                ->groupBy('userId')
                ->orderBy('weight', 'DESC')
                ->getQuery()
                ->getResult();
    }
}
