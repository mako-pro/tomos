<?php

namespace placer\tomos\controllers\auth;

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

            $this->response->setBody('Forbidden 403!');

            return $this->response->send();
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

        $validator = $this->validator->create(['token' => $token], $rules);

        if (! $validator->isValid() || ! $this->gatekeeper->activateUser($token))
        {
            $this->response->setStatus('400');

            $this->response->setBody('Bad Request 400!');

            return $this->response->send();
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
