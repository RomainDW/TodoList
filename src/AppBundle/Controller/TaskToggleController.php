<?php


namespace AppBundle\Controller;


use AppBundle\Entity\Task;
use AppBundle\Exception\TaskNotFoundException;
use AppBundle\Service\TaskService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TaskToggleController extends Controller
{
    /**
     * Toggle the task status (done or not done) by ID.
     *
     * @Route("/tasks/{id}/toggle", name="task_toggle", methods={"POST"})
     * @param Request $request
     * @param TaskService $taskService
     * @return RedirectResponse
     * @throws TaskNotFoundException
     */
    public function toggleTaskAction(Request $request, TaskService $taskService)
    {
        /** @var Task $task */
        $task = $taskService->find($request->attributes->get('id'));

        $taskService->toggle($task);

        if ($task->isDone()) {
            $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));
        } else {
            $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme non terminée.', $task->getTitle()));
        }

        return $this->redirectToRoute('task_list');
    }
}