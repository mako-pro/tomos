<?php

return [

    // Login
    '100' => 'The account has been banned',
    '101' => 'The account has not been activated',
    '102' => 'The provided credentials are invalid',
    '103' => 'The account has been temporarily locked due to too many failed login attempts',

    'register' => [
        'success'      => 'success | Successfully registered! Now login, please.',
    ],

    'verify' => [
        'success'      => 'success | Your Email address is verified! Now login, please.',
    ],

    'forgot' => [
        'bad-email'    => 'We can\'t find a user with that email address',
        'no-activated' => 'Account with that email address is not activated',
        'banned'       => 'Account with that email address has been banned',
    ],

    'reset' => [
        'success'      => 'success | Your password has been reset! Now login, please.',
        'fail'         => 'This password reset token is invalid',
    ]

];
