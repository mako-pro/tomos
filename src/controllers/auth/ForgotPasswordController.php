<?php

namespace placer\tomos\controllers\auth;

use mako\http\routing\Controller;

class ForgotPasswordController extends Controller
{
    /**
     * Outputs the request reset password form
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

        return $this->view->render('tomos::auth.forgot');
    }

    /**
     * Processing a user password reset request
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

            return 'Forbidden 403!';
        }

        return $this->view->render('tomos::auth.confirm');
    }

}
