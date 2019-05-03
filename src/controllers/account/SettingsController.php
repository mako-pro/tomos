<?php

namespace placer\tomos\controllers\account;

use mako\http\routing\Controller;
use placer\tomos\models\User;

class SettingsController extends Controller
{
    /**
     * Outputs the account settings page
     *
     * @return mixed
     */
    public function page()
    {
        $user = $this->gatekeeper->getUser();

        if ($user === false)
        {
            return $this->redirectResponse(
                $this->urlBuilder->toRoute('tomos.login.page')
            );
        }

        $user = User::get($user->id);

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
