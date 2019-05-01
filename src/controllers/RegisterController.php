<?php

namespace placer\tomos\controllers;

use mako\http\routing\Controller;
use placer\tomos\Tomos;

class RegisterController extends Controller
{
    /**
     * Outputs the registration form
     *
     * @return mixed
     */
    public function page()
    {
        if ($this->gatekeeper->isLoggedIn())
        {
            return $this->redirectResponse(
                $this->urlBuilder->toRoute('tomos.dashboard.page')
            );
        }
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
        {
            return $this->redirectResponse('/');
        }

        if ($this->gatekeeper->isLoggedIn())
        {
            return $this->jsonResponse([
                'url' => $this->urlBuilder->toRoute('tomos.dashboard.page')
            ]);
        }

        $postData = $this->request->getPost()->all();
        $rules    = $this->config->get('tomos::rules.register');
        $check    = $this->validator->create($postData, $rules);

        if (! $check->isValid())
        {
            $this->response->setStatus('400');

            return $this->jsonResponse([
                'errors'   => true,
                'messages' => $check->getErrors()
            ]);
        }

        $user = $this->gatekeeper->createUser(
            $postData['email'],
            $postData['username'],
            $postData['password'],
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
                'message', $this->i18n->get('tomos::auth.register.success')
            );

            $route = 'tomos.login.page';
        }

        return $this->jsonResponse([
            'url' => $this->urlBuilder->toRoute($route)
        ]);
    }

}
