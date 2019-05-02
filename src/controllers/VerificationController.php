<?php

namespace placer\tomos\controllers;

use mako\http\routing\Controller;

class VerificationController extends Controller
{
    /**
     * Outputs the page with message
     *
     * @return string
     */
    public function page()
    {
        if (! $this->session->getFlash('email'))
        {
            $this->response->setStatus('403');

            return 'Forbidden 403!';
        }

        return $this->view->render('tomos::auth.verify');
    }

    /**
     * Activate new user by verify token
     *
     * @param  string $token Action token
     * @return Response
     */
    public function verify($token)
    {
        $rules = $this->config->get('tomos::rules.action');
        $check = $this->validator->create(['token' => $token], $rules);

        if (! $check->isValid() || ! $this->gatekeeper->activateUser($token))
        {
            $this->response->setStatus('403');
            return 'Forbidden 403!';
        }

        $this->session->putFlash(
            'message', $this->i18n->get('tomos::auth.verify.success')
        );

        return $this->redirectResponse('tomos.login.page');
    }

    /**
     * Resend the verification link
     *
     * @return mixed
     */
    public function resend()
    {
        return '';
    }

}
