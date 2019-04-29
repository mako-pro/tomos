<?php

namespace placer\tomos\controllers;

use mako\http\routing\Controller;

class LoginController extends Controller
{
    public function page()
    {
        return $this->view->render('tomos::auth.login');
    }

    public function handler()
    {
        return $this->jsonResponse(['body' => __METHOD__]);
    }

}
