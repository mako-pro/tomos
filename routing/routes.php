<?php

$routes->group(['prefix' => $options['prefix'], 'namespace' => 'placer\tomos\controllers'], function ($routes) use($options)
{
    $routes->get('/dashboard', 'DashboardController::page', 'tomos.dashboard.page');

    if ($options['register'] ?? false)
    {
        $routes->get('/register', 'RegisterController::page', 'tomos.register.page');
        $routes->post('/register', 'RegisterController::handler', 'tomos.register.handler');
    }

    if ($options['reset'] ?? false)
    {
        $routes->get('/password/reset', 'ForgotPasswordController::page', 'tomos.forgot.page');
        $routes->post('/password/email', 'ForgotPasswordController::handler', 'tomos.forgot.handler');
        $routes->get('/password/confirm', 'ForgotPasswordController::confirm', 'tomos.forgot.confirm');

        $routes->get('/password/reset/{token}', 'ResetPasswordController::page', 'tomos.reset.page');
        $routes->post('/password/resets', 'ResetPasswordController::handler', 'tomos.reset.handler');
    }

    if ($options['verify'] ?? false)
    {
        $routes->get('/email/verify', 'VerificationController::page', 'tomos.verification.page');
        $routes->get('/email/verify/{token}', 'VerificationController::verify', 'tomos.verification.verify');
        $routes->get('/email/resend', 'VerificationController::resend', 'tomos.verification.resend');
    }

    $routes->get('/login', 'LoginController::page', 'tomos.login.page');
    $routes->post('/login', 'LoginController::handler', 'tomos.login.handler');
    $routes->get('/logout', 'LogoutController::page', 'tomos.logout.page');

});
