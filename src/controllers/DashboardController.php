<?php

namespace placer\tomos\controllers;

use mako\http\routing\Controller;

class DashboardController extends Controller
{
    /**
     * Outputs the dashboard page
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

        $user = $this->gatekeeper->getUser();

        $this->view->render('tomos::dashboard', compact('user'));
    }

}
