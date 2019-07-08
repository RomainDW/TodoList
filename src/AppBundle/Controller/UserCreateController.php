<?php


namespace AppBundle\Controller;

use AppBundle\Form\UserType;
use AppBundle\FormHandler\CreateUserFormHandler;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserCreateController extends Controller
{
    /**
     * Show the user creation page.
     *
     * @Route("/users/create", name="user_create")
     * @param Request $request
     * @param CreateUserFormHandler $formHandler
     * @return RedirectResponse|Response
     */
    public function create(
        Request $request,
        CreateUserFormHandler $formHandler
    ): Response {
        $form = $this->createForm(UserType::class);
        $form->handleRequest($request);

        if ($formHandler->handle($form)) {
            $this->addFlash('success', "L'utilisateur a bien été ajouté.");
            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/create.html.twig', ['form' => $form->createView()]);
    }
}
