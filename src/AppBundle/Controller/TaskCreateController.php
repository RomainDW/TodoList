<?php


namespace AppBundle\Controller;

use AppBundle\Form\TaskType;
use AppBundle\FormHandler\CreateTaskFormHandler;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskCreateController extends Controller
{
    /**
     * Show the task creation page.
     *
     * @Route("/tasks/create", name="task_create")
     * @param Request $request
     * @param CreateTaskFormHandler $formHandler
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request, CreateTaskFormHandler $formHandler)
    {
        $form = $this->createForm(TaskType::class);

        $form->handleRequest($request);

        if ($formHandler->handle($form, $this->getUser())) {
            $this->addFlash('success', 'La tâche a été bien été ajoutée.');
            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }
}