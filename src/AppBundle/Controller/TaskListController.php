<?php


namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class TaskListController extends Controller
{
    /**
     * Show the list of tasks.
     *
     * @Route("/tasks", name="task_list")
     */
    public function listAction()
    {
        $tasks = $this->getDoctrine()->getRepository('AppBundle:Task')->findAll();

        return $this->render('task/list.html.twig', ['tasks' => $tasks]);
    }
}