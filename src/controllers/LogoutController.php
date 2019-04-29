<?php

namespace placer\tomos\controllers;

use mako\http\routing\Controller;

class LogoutController extends Controller
{
    public function page()
    {
        return $this->view->render('tomos::auth.logout');
    }

}
