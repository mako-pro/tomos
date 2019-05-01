<?php

namespace placer\tomos\controllers;

use mako\http\routing\Controller;

class LogoutController extends Controller
{
    /**
     * Logout the user
     * Outputs the logout page
     *
     * @return mixed
     */
    public function page()
    {
        if ($this->gatekeeper->isGuest())
        {
            return $this->redirectResponse(
                $this->urlBuilder->toRoute('tomos.login.page')
            );
        }

        $this->gatekeeper->logout();

        return $this->view->render('tomos::auth.logout');
    }

}
