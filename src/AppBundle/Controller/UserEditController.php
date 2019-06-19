<?php

namespace AppBundle\Controller;

use AppBundle\DTO\UserDTO;
use AppBundle\Entity\User;
use AppBundle\Form\UserType;
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
     * @param User $user
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function editAction(User $user, Request $request)
    {
        $userDTO = new UserDTO();
        $userDTO->createFromUser($user);

        $form = $this->createForm(UserType::class, $userDTO);

        $form->handleRequest($request);

        if ($form->isValid()) {
            /** @var UserDTO $userDTO */
            $userUpdateDTO = $form->getData();
            $user->update($userUpdateDTO->username, $userUpdateDTO->password, $userUpdateDTO->email);

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', "L'utilisateur a bien été modifié");

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/edit.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }
}
