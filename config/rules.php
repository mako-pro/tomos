<?php

return [

    'register' => [
        'username'     => ['required', 'min_length(4)', 'max_length(16)', 'unique("users", "username")'],
        'email'        => ['required', 'email', 'unique("users", "email")'],
        'password'     => ['required', 'min_length(6)', 'max_length(32)', 'match("password_confirmation")'],
        'accept_terms' => ['required'],
    ],

    'login' => [
        'email'        => ['required', 'email', 'exists("users", "email")'],
        'password'     => ['required', 'min_length(6)', 'max_length(32)'],
    ],

    'action' => [
        'token'        => ['required', 'exact_length(64)', 'hex'],
    ],

    'forgot' => [
        'email'        => ['required', 'email', 'exists("users", "email")'],
    ],

    'reset' => [
        'email'        => ['required', 'email'],
        'password'     => ['required', 'min_length(6)', 'max_length(32)', 'match("password_confirmation")'],
    ],

];
