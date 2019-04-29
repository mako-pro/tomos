<?php

namespace placer\tomos\controllers;

use mako\http\routing\Controller;
use placer\tomos\Tomos;

class RegisterController extends Controller
{
    /**
     * Output the registration form
     *
     * @return string
     */
    public function page()
    {
        return $this->view->render('tomos::auth.register');
    }

    /**
     * Creates the new user
     *
     * @param  Tomos  $tomos
     * @return Response
     */
    public function handler(Tomos $tomos)
    {
        if (! $this->request->isAjax())
            return $this->redirectResponse('/');

        $data = $this->request->getPost()->all();

        if ($errors = $tomos->validateRegister($data))
        {
            $this->response->setStatus('400');

            return $this->jsonResponse([
                'errors'   => true,
                'messages' => $errors
            ]);
        }

        $user = $this->gatekeeper->createUser(
            $data['email'],
            $data['username'],
            $data['password'],
            ! $tomos->verify
        );

        $groupRepository = $this->gatekeeper->getGroupRepository();
        $usersGroup = $groupRepository->getByName($tomos->default_group);
        $usersGroup->addUser($user);

        if ($tomos->verify === true)
        {
            $tomos->sendActivationEmail($user->id);
            $this->session->putFlash('email', $user->email);
            $route = 'tomos.verification.page';
        }
        else
        {
            $this->session->putFlash(
                'message', 'success|Successfully registered.|Now login, please.'
            );
            $route = 'tomos.login.page';
        }

        return $this->jsonResponse(
            $this->urlBuilder->toRoute($route)
        );
    }

}
