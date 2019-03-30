<?php

namespace placer\tomos\controllers;

use mako\http\routing\Controller;

class WelcomeController extends Controller
{
    public function index()
    {
        $data = $this->tomos->verify;

        return $this->view->render('tomos::welcome', ['data' => (int) $data]);
    }

}
