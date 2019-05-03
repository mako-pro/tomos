<?php

namespace placer\tomos\controllers\auth;

use mako\http\routing\Controller;

class LoginController extends Controller
{
    /**
     * Outputs the login form
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
        return $this->view->render('tomos::auth.login');
    }

    /**
     * User authentication action
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
