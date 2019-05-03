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
        $postData = $this->request->getPost()->all();
        $rules    = $this->config->get('tomos::rules.login');
        $check    = $this->validator->create($postData, $rules);

        if (! $check->isValid())
        {
            return $this->jsonResponse([
                'message' => $this->i18n->get("tomos::auth.102")
            ]);
        }

        $authResult = $this->gatekeeper->login(
            $postData['email'],
            $postData['password'],
            $postData['remember'] ?? false
        );

        if ($authResult === true)
        {
            return $this->jsonResponse([
                'url' => $this->urlBuilder->toRoute('tomos.dashboard.page')
            ]);
        }

        return $this->jsonResponse([
            'message' => $this->i18n->get("tomos::auth.{$authResult}")
        ]);
    }

}
