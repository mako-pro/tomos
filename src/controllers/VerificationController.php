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
        return $this->view->render('tomos::auth.verify');
    }

    /**
     * Activate new user by verify token
     *
     * @param  string $id Action token
     * @return Response
     */
    public function verify($id = null)
    {
        if ($this->gatekeeper->activateUser($id))
        {
            $this->session->putFlash(
                'message', 'success|Your Email address is verified.|Now login, please.'
            );
            return $this->redirectResponse('tomos.login.page');
        }

        $this->response->setStatus('403');
        return 'Forbidden 403!';
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
