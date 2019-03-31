<?php

namespace placer\tomos\controllers;

use mako\http\routing\Controller;

class ResetPasswordController extends Controller
{
    public function link($token = null)
    {
        return __METHOD__;
    }

    public function handler()
    {
        return $this->jsonResponse(['body' => __METHOD__]);
    }

}
