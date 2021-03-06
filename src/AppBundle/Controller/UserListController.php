<?php


namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserListController extends Controller
{
    /**
     * Show the list of Users.
     *
     * @Route("/users", name="user_list")
     */
    public function list(): Response
    {
        $users = $this->getDoctrine()->getRepository('AppBundle:User')->findAll();

        return $this->render('user/list.html.twig', ['users' => $users]);
    }
}
