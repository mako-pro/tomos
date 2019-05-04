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
        if (! $user = $this->tomos->getCurrentUser())
        {
            return $this->redirectResponse(
                $this->urlBuilder->toRoute('tomos.login.page')
            );
        }

        $this->view->render('tomos::dashboard', compact('user'));
    }

}
