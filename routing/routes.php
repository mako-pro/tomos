<?php

$routes->group(['prefix' => $options['prefix'], 'namespace' => 'placer\tomos\controllers'], function ($routes) use($options)
{
    $routes->get('/dashboard', 'DashboardController::page', 'tomos.dashboard.page');

    $routes->get('/account/profile', 'account\ProfileController::page', 'tomos.profile.page');
    $routes->get('/account/settings', 'account\SettingsController::page', 'tomos.settings.page');
    $routes->post('/account/settings', 'account\SettingsController::handler', 'tomos.settings.handler');

    if ($options['register'] ?? false)
    {
        $routes->get('/register', 'auth\RegisterController::page', 'tomos.register.page');
        $routes->post('/register', 'auth\RegisterController::handler', 'tomos.register.handler');
    }

    if ($options['reset'] ?? false)
    {
        $routes->get('/password/reset', 'auth\ForgotPasswordController::page', 'tomos.forgot.page');
        $routes->post('/password/email', 'auth\ForgotPasswordController::handler', 'tomos.forgot.handler');
        $routes->get('/password/confirm', 'auth\ForgotPasswordController::confirm', 'tomos.forgot.confirm');

        $routes->get('/password/reset/{token}', 'auth\ResetPasswordController::page', 'tomos.reset.page');
        $routes->post('/password/resets', 'auth\ResetPasswordController::handler', 'tomos.reset.handler');
    }

    if ($options['verify'] ?? false)
    {
        $routes->get('/email/verify', 'auth\VerificationController::page', 'tomos.verification.page');
        $routes->get('/email/verify/{token}', 'auth\VerificationController::verify', 'tomos.verification.verify');
        $routes->get('/email/resend', 'auth\VerificationController::resend', 'tomos.verification.resend');
    }

    $routes->get('/login', 'auth\LoginController::page', 'tomos.login.page');
    $routes->post('/login', 'auth\LoginController::handler', 'tomos.login.handler');
    $routes->get('/logout', 'auth\LogoutController::page', 'tomos.logout.page');

});
