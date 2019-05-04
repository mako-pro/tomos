<?php

namespace placer\tomos\controllers\account;

use mako\http\routing\Controller;

class SettingsController extends Controller
{
    /**
     * Outputs the account settings page
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

        $this->view->render('tomos::account.settings', compact('user'));
    }

    /**
     * Manage the account settings
     *
     * @return mixed
     */
    public function handler()
    {

    }

}
