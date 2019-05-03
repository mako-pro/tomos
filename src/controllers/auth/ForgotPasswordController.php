<?php

namespace placer\tomos\controllers\auth;

use mako\http\routing\Controller;

class ForgotPasswordController extends Controller
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
     * Outputs the request reset password form
     *
     * @return mixed
     */
    public function page()
    {
        return $this->view->render('tomos::auth.forgot');
    }

    /**
     * Processing a user password reset request
     *
     * @return Response
     */
    public function handler()
    {
        $postData = $this->request->getPost()->all();
        $rules    = $this->config->get('tomos::rules.forgot');
        $check    = $this->validator->create($postData, $rules);

        if (! $check->isValid())
        {
            return $this->jsonResponse([
                'message' => $this->i18n->get("tomos::auth.forgot.bademail")
            ]);
        }

        $user = $this->gatekeeper->getUserRepository()
            ->getByEmail($postData['email']);

        if ($this->tomos->verify === true && ! $user->isActivated())
        {
             return $this->jsonResponse([
                'message' => $this->i18n->get("tomos::auth.forgot.noactivated")
            ]);
        }

        if ($user->isBanned())
        {
             return $this->jsonResponse([
                'message' => $this->i18n->get("tomos::auth.forgot.banned")
            ]);
        }

        $this->tomos->sendUserEmail($user->id, 'forgot');

        $this->session->putFlash('email', $user->email);

        return $this->jsonResponse([
            'url' => $this->urlBuilder->toRoute('tomos.forgot.confirm')
        ]);
    }

    /**
     * Outputs the confirm email page
     *
     * @return string
     */
    public function confirm()
    {
        if (! $this->session->getFlash('email'))
        {
            $this->response->setStatus('403');

            $this->response->setBody('Forbidden 403!');

            return $this->response->send();
        }

        return $this->view->render('tomos::auth.confirm');
    }

}
