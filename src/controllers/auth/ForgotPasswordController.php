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
        $post  = $this->request->getPost();
        $rules = $this->config->get('tomos::rules.forgot');

        $validator = $this->validator->create($post->all(), $rules);

        if (! $validator->isValid())
        {
            return $this->jsonResponse([
                'message' => $this->i18n->get("tomos::auth.forgot.bad-email")
            ]);
        }

        $user = $this->gatekeeper->getUserRepository()
            ->getByEmail($post->get('email'));

        if (! $user->isActivated())
        {
             return $this->jsonResponse([
                'message' => $this->i18n->get("tomos::auth.forgot.no-activated")
            ]);
        }

        if ($user->isBanned())
        {
             return $this->jsonResponse([
                'message' => $this->i18n->get("tomos::auth.forgot.banned")
            ]);
        }

        $this->tomos->sendUserEmail($user->getId(), 'forgot');

        $this->session->putFlash('email', $user->getEmail());

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
