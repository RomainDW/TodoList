<?php

namespace AppBundle\Controller;

use AppBundle\DTO\UserDTO;
use AppBundle\Entity\User;
use AppBundle\Exception\UserNotFoundException;
use AppBundle\Form\UserType;
use AppBundle\FormHandler\EditUserFormHandler;
use AppBundle\Service\UserService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserEditController extends Controller
{
    /**
     * Show the user edition page
     *
     * @Route("/users/{id}/edit", name="user_edit")
     * @param Request $request
     * @param EditUserFormHandler $formHandler
     * @param UserService $userService
     * @return RedirectResponse|Response
     * @throws UserNotFoundException
     */
    public function edit(
        Request $request,
        EditUserFormHandler $formHandler,
        UserService $userService
    ): Response {
        /** @var User $user */
        $user = $userService->find($request->attributes->get('id'));
        $userDTO = new UserDTO();
        $userDTO->createFromUser($user);

        $form = $this->createForm(UserType::class, $userDTO);
        $form->handleRequest($request);

        if ($formHandler->handle($form, $user)) {
            $this->addFlash('success', "L'utilisateur a bien été modifié");
            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/edit.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }
}
