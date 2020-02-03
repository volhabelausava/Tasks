<?php

namespace App\Service;

use App\Entity\Comment;
use App\Entity\Task;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class TaskManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Gets the array of the user tasks with comments.
     *
     * @param $user User
     * @return array
     */
    public function getTasksWithCommentsBy(User $user) : array
    {
        $tasks = $this->em->getRepository(Task::class)->findArrayBy($user);
        $tasksWithComments = [];
        foreach ($tasks as $task) {
            $task['comments'] = $this->em->getRepository(Comment::class)->findArrayBy($task);
            $task['createDate'] = $task['createDate']->format(\DateTime::ISO8601);
            $tasksWithComments[] = $task;
        }

        return $tasksWithComments;
    }
}