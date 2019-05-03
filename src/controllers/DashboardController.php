<?php

namespace placer\tomos\controllers;

use mako\http\routing\Controller;
use placer\tomos\models\Profile;

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

        $profile = Profile::get($user->id);

        $this->view->render('tomos::dashboard', compact('user', 'profile'));
    }

}
