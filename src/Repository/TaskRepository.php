<?php

namespace App\Repository;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Task|null find($id, $lockMode = null, $lockVersion = null)
 * @method Task|null findOneBy(array $criteria, array $orderBy = null)
 * @method Task[]    findAll()
 * @method Task[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

     /**
      * Gets the array of results for the filtered by status query.
      *
      * @param $status int
      * @param $user
      * @return array
      */
    public function findByStatusField($status, $user)
    {
        $query = $this->_em->createQuery(
            'SELECT t.id, t.name, t.description, COUNT(c.task) AS comments_quantity
                FROM App\Entity\Task t
                LEFT JOIN t.comments c
                WHERE t.status=:status and t.user=:id
                GROUP BY t.id
                ORDER BY t.createDate ASC'
        );
        $query->setParameter('status', $status);
        $query->setParameter('id', $user->getId());

        return $query->getArrayResult();
    }

    // /**
    //  * @return Task[] Returns an array of Task objects
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
    public function findOneBySomeField($value): ?Task
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
