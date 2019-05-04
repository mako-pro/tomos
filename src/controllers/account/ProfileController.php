<?php

namespace placer\tomos\controllers\account;

use mako\http\routing\Controller;

class ProfileController extends Controller
{
    /**
     * Outputs the user profile page
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

        $this->view->render('tomos::account.profile', compact('user'));
    }

}
