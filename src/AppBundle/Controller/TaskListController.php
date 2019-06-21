<?php


namespace AppBundle\Controller;


use AppBundle\Entity\Task;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class TaskListController extends Controller
{
    const MAX_RESULT = 6;

    /**
     * Show the list of tasks.
     *
     * @Route("/tasks", name="task_list")
     * @param Request $request
     * @return Response
     * @throws NonUniqueResultException
     */
    public function listAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Task::class);

        $page = $request->get('page') ? $request->get('page') : 1;
        $totalPage = $repository->findMaxNumberOfPage(self::MAX_RESULT);

        if ($page > $totalPage || $page < 0) {
            throw new NotFoundHttpException("La page n'existe pas");
        }

        $tasks = $repository->findByPage($page, self::MAX_RESULT);

        return $this->render('task/list.html.twig', [
            'tasks' => $tasks,
            'numberOfPages' => $totalPage
        ]);
    }
}