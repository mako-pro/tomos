<?php

namespace placer\tomos\controllers\auth;

use mako\http\routing\Controller;

class LoginController extends Controller
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
     * Outputs the login form
     *
     * @return mixed
     */
    public function page()
    {
        return $this->view->render('tomos::auth.login');
    }

    /**
     * User authentication action
     *
     * @return Response
     */
    public function handler()
    {
        $post  = $this->request->getPost();
        $rules = $this->config->get('tomos::rules.login');

        $validator = $this->validator->create($post->all(), $rules);

        if (! $validator->isValid())
        {
            return $this->jsonResponse([
                'message' => $this->i18n->get("tomos::auth.102")
            ]);
        }

        $result = $this->gatekeeper->login(
            $post->get('email'),
            $post->get('password'),
            $post->get('remember') ? true : false
        );

        if ($result === true)
        {
            $this->tomos->touchActivity('auth.login');

            return $this->jsonResponse([
                'url' => $this->urlBuilder->toRoute('tomos.dashboard.page')
            ]);
        }

        return $this->jsonResponse([
            'message' => $this->i18n->get("tomos::auth.{$result}")
        ]);
    }

}
