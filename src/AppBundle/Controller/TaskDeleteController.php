<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Task;
use AppBundle\Exception\TaskNotFoundException;
use AppBundle\Service\TaskService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class TaskDeleteController extends Controller
{
    /**
     * Delete a task by ID
     *
     * @Route("/tasks/{id}/delete", name="task_delete", methods={"POST"})
     * @param Request $request
     * @param TaskService $taskService
     * @return RedirectResponse
     * @throws TaskNotFoundException
     */
    public function deleteTaskAction(Request $request, TaskService $taskService)
    {
        /** @var Task $task */
        $task = $this->getDoctrine()->getRepository(Task::class)->find($request->attributes->get('id'));

        if (is_null($task)) {
            throw new TaskNotFoundException("La tâche n'existe pas.");
        }

        $taskService->remove($task);

        $this->addFlash('success', 'La tâche a bien été supprimée.');

        return $this->redirectToRoute('task_list');
    }
}
