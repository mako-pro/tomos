<?php

namespace placer\tomos\controllers\auth;

use mako\http\routing\Controller;
use placer\tomos\models\Profile;
use placer\tomos\models\User;

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
     * @return Response
     */
    public function handler()
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
            ! $this->tomos->verify
        );

        $groupRepository = $this->gatekeeper->getGroupRepository();
        $usersGroup = $groupRepository->getByName($this->tomos->default_group);
        $usersGroup->addUser($user);

        $tomosUser = User::get($user->id);
        $tomosUser->profile()->create(new Profile);

        if ($this->tomos->verify === true)
        {
            $this->tomos->sendUserEmail($user->id, 'verify');
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
