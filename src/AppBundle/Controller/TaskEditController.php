<?php


namespace AppBundle\Controller;

use AppBundle\DTO\TaskDTO;
use AppBundle\Entity\Task;
use AppBundle\Exception\TaskNotFoundException;
use AppBundle\Form\TaskType;
use AppBundle\FormHandler\EditTaskFormHandler;
use AppBundle\Service\TaskService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskEditController extends Controller
{
    /**
     * Show the task edition page.
     *
     * @Route("/tasks/{id}/edit", name="task_edit")
     * @param Request $request
     * @param EditTaskFormHandler $editTaskFormHandler
     * @param TaskService $taskService
     * @return RedirectResponse|Response
     * @throws TaskNotFoundException
     */
    public function editAction(Request $request, EditTaskFormHandler $editTaskFormHandler, TaskService $taskService): RedirectResponse
    {
        /** @var Task $task */
        $task = $taskService->find($request->attributes->get('id'));
        $taskDTO = new TaskDTO();
        $taskDTO->createFromTask($task);
        $form = $this->createForm(TaskType::class, $taskDTO);

        $form->handleRequest($request);

        if ($editTaskFormHandler->handle($form, $task)) {
            $this->addFlash('success', 'La tâche a bien été modifiée.');
            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }
}
