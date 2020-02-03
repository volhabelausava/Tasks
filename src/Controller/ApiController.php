<?php

namespace App\Controller;

use App\Service\TaskManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncode;

/**
 * @Route("/api")
 */
class ApiController extends AbstractController
{
    /**
     * @Route("/tasks", name="api_get_tasks")
     * @param $taskManager TaskManager
     * @return Response
     */
    public function apiGetTasks(TaskManager $taskManager) : Response
    {
        $encode = new JsonEncode(JSON_UNESCAPED_UNICODE);
        $tasks = $taskManager->getTasksWithCommentsBy($this->getUser());
        $jsonData = $encode->encode($tasks, 'json');

        return new Response($jsonData, 200, ['Content-Type' => 'application/json']);
    }
}