<?php

namespace placer\tomos\controllers;

use mako\http\routing\Controller;
use placer\tomos\models\User;

class DashboardController extends Controller
{
    /**
     * Outputs the dashboard page
     *
     * @return mixed
     */
    public function page()
    {
        if (! $user = $this->gatekeeper->getUser())
        {
            return $this->redirectResponse(
                $this->urlBuilder->toRoute('tomos.login.page')
            );
        }

        $user = User::get($user->id);

        $this->view->render('tomos::dashboard', compact('user'));
    }

}
