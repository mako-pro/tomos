<?php

namespace placer\tomos\controllers\auth;

use mako\http\routing\Controller;
use placer\tomos\models\Location;
use placer\tomos\models\Profile;
use placer\tomos\models\Setting;
use placer\tomos\models\User;

class RegisterController extends Controller
{
    /**
     * Before action
     *
     * @return mixed
     */
    public function beforeAction()
    {
        if ($this->gatekeeper->isLoggedIn())
        {
            $route = 'tomos.dashboard.page';

            $url = $this->urlBuilder->toRoute($route);

            if (! $this->request->isAjax())
            {
                return $this->redirectResponse($url);
            }

            return $this->jsonResponse(['url' => $url]);
        }
    }

    /**
     * Outputs the registration form
     *
     * @return mixed
     */
    public function page()
    {
        return $this->view->render('tomos::auth.register');
    }

    /**
     * Creates the new user
     *
     * @return Response
     */
    public function handler()
    {
        $post  = $this->request->getPost();
        $rules = $this->config->get('tomos::rules.register');

        $validator = $this->validator->create($post->all(), $rules);

        if (! $validator->isValid())
        {
            $this->response->setStatus('400');

            return $this->jsonResponse([
                'errors'   => true,
                'messages' => $validator->getErrors()
            ]);
        }

        $user = $this->gatekeeper->createUser(
            $post->get('email'),
            $post->get('username'),
            $post->get('password'),
            ! $this->tomos->verify
        );

        $defaultGroup = $this->tomos->defaultGroup ?? 'default';
        $groupRepository = $this->gatekeeper->getGroupRepository();
        $usersGroup = $groupRepository->getByName($defaultGroup);
        $usersGroup->addUser($user);

        $tomosUser = User::get($user->getId());
        $tomosUser->profile()->create(new Profile);
        $tomosUser->setting()->create(new Setting);
        $tomosUser->location()->create(new Location);

        if ($this->tomos->verify === true)
        {
            $this->tomos->sendUserEmail($user->getId(), 'verify');
            $this->session->putFlash('email', $user->getEmail());
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
