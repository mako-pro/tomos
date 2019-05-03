<?php

namespace placer\tomos\controllers\account;

use mako\http\routing\Controller;
use placer\tomos\models\User;

class ProfileController extends Controller
{
    /**
     * Outputs the user profile page
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

        $this->view->render('tomos::account.profile', compact('user'));
    }

}
