<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Task;
use App\Form\CommentType;
use App\Form\TaskType;
use App\Repository\TaskRepository;
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
     */
    public function new(Request $request): Response
    {
        $task = new Task();
        $formTask = $this->createForm(TaskType::class, $task);
        $formTask->handleRequest($request);

        if ($formTask->isSubmitted() && $formTask->isValid()) {
            $task->setCreateDate(new \DateTime(null, new \DateTimeZone('Europe/Minsk') ));
            $task->setUser($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($task);
            $entityManager->flush();

            $this-> addFlash('success', 'Задача добавлена');
            return $this->redirectToRoute('task_index');
        }

        return $this->render('task/new.html.twig', [
            'task' => $task,
            'formTask' => $formTask->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="task_show", methods={"GET"})
     */
    public function show(Task $task): Response
    {
        return $this->render('task/show.html.twig', [
            'task' => $task,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="task_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Task $task): Response
    {
        $comment = new Comment();

        $formTask = $this->createForm(TaskType::class, $task);
        $formComment = $this->createForm(CommentType::class, $comment);

        $formTask->handleRequest($request);
        $formComment->handleRequest($request);

        if ($formTask->isSubmitted() && $formTask->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Задача обновлена');

            return $this->redirectToRoute('task_index');
        }

        if ($formComment->isSubmitted() && $formComment->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $comment->setTask($task);
            $entityManager->persist($comment);
            $entityManager->flush();
            $this->addFlash('success', 'Комментарий добавлен');

            return $this->redirectToRoute('task_edit', ['id' => $task->getId()]);
        }

        return $this->render('task/edit.html.twig', [
            'task' => $task,
            'formTask' => $formTask->createView(),
            'formComment' => $formComment->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="task_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Task $task): Response
    {
        if ($this->isCsrfTokenValid('delete'.$task->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($task);
            $entityManager->flush();
        }

        return $this->redirectToRoute('task_index');
    }
}
