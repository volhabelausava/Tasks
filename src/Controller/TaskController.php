<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Task;
use App\Exception\StatusNotValidException;
use App\Form\CommentType;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/")
 */
class TaskController extends AbstractController
{
    /**
     * @Route("/", name="task_index", methods={"GET"})
     * @param $taskRepository TaskRepository
     * @return Response
     */
    public function index(TaskRepository $taskRepository): Response
    {
        $user = $this->getUser();
        $tasksToDo = $taskRepository->findByStatusField(Task::STATUS_TODO, $user);
        $tasksDoing = $taskRepository->findByStatusField(Task::STATUS_DOING, $user);
        $tasksDone = $taskRepository->findByStatusField(Task::STATUS_DONE, $user);

        return $this->render('task/index.html.twig', [
            'tasksToDo' => $tasksToDo,
            'tasksDoing' => $tasksDoing,
            'tasksDone' => $tasksDone
        ]);
    }

    /**
     * @Route("/new", name="task_new", methods={"GET","POST"})
     * @param $request Request
     * @param $logger LoggerInterface
     * @return Response
     * @throws \Exception
     */
    public function new(Request $request, LoggerInterface $logger): Response
    {
        $task = new Task();
        $formTask = $this->createForm(TaskType::class, $task);
        try {
            $formTask->handleRequest($request);
        } catch (StatusNotValidException $error){
            $logger->error($error);
            $this->addFlash('error', 'Статус задачи задан неверно.');
        }

        if ($formTask->isSubmitted() && $formTask->isValid()) {
            $task->setCreateDate(new \DateTime(null, new \DateTimeZone('Europe/Minsk') ));
            $task->setUser($this->getUser());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($task);
            $entityManager->flush();

            $this->addFlash('success', 'Задача добавлена');

            return $this->redirectToRoute('task_index');
        }

        return $this->render('task/new.html.twig', [
            'task' => $task,
            'formTask' => $formTask->createView(),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="task_edit", methods={"GET","POST"})
     * @param $request Request
     * @param $task Task
     * @param $logger LoggerInterface
     * @return Response
     */
    public function edit(Request $request, Task $task, LoggerInterface $logger): Response
    {
        $comment = new Comment();

        $formTask = $this->createForm(TaskType::class, $task);
        try {
            $formTask->handleRequest($request);
        } catch (StatusNotValidException $error){
            $logger->error($error);
            $this->addFlash('error', 'Статус задачи задан неверно.');
        }

        $formComment = $this->createForm(CommentType::class, $comment);
        $formComment->handleRequest($request);

        if ($formTask->isSubmitted() && $formTask->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('success', 'Задача обновлена');

            return $this->redirectToRoute('task_index');
        }

        if ($formComment->isSubmitted() && $formComment->isValid()) {
            $comment->setTask($task);

            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            $this->addFlash('success', 'Комментарий добавлен');

            return $this->redirectToRoute('task_edit', ['id' => $task->getId()]);
        }

        return $this->render('task/edit.html.twig', [
            'task' => $task,
            'formTask' => $formTask->createView(),
            'formComment' => $formComment->createView(),
        ]);
    }
}
