<?php

namespace placer\tomos\controllers;

use mako\http\routing\Controller;

class ForgotPasswordController extends Controller
{
    public function page()
    {
        return __METHOD__;
    }

    public function handler()
    {
        return $this->jsonResponse(['body' => __METHOD__]);
    }

}
