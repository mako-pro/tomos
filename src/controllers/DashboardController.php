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
        if (! $user = $this->gatekeeper->getUser())
            return $this->redirectResponse('tomos.login.page');

        return $this->view->render('tomos::dashboard', [
            'profile' => Profile::getByUserId($user->getId())->toArray(),
            'user'    => $user->toArray(),
        ]);
    }

}
