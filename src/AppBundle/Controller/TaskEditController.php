<?php


namespace AppBundle\Controller;


use AppBundle\DTO\TaskDTO;
use AppBundle\Entity\Task;
use AppBundle\Form\TaskType;
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
     * @param Task $task
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function editAction(Task $task, Request $request)
    {
        $taskDTO = new TaskDTO();
        $taskDTO->createFromTask($task);
        $form = $this->createForm(TaskType::class, $taskDTO);

        $form->handleRequest($request);

        if ($form->isValid()) {

            /** @var TaskDTO $taskUpdateDTO */
            $taskUpdateDTO = $form->getData();
            $task->update($taskUpdateDTO->title, $taskUpdateDTO->content);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'La tâche a bien été modifiée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }
}