<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }
    /**
     * Gets the array of comments data for the task.
     *
     * @param $task array
     * @return array
     */
    public function findArrayBy($task) : array
    {
        $query = $this->_em->createQuery(
            'SELECT c.content
                FROM App\Entity\Comment c
                WHERE c.task=:id'
        );
        $query->setParameter('id', $task['id']);

        return $query->getArrayResult();
    }
}
