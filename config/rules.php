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

    'account_profile'  => [
        'first_name'   => ['required', 'alpha_unicode', 'min_length(3)', 'max_length(32)'],
        'last_name'    => ['required', 'alpha_unicode', 'min_length(3)', 'max_length(32)'],
        'birthday'     => ['required', 'date("Y-m-d")', 'before("Y-m-d","2010-12-31")', 'after("Y-m-d","1919-12-31")'],
        'phone'        => ['required', 'max_length(16)'],
        'email'        => ['required', 'email', 'max_length(64)', 'unique("users", "email")'],
        'heading'      => ['required', 'min_length(6)', 'max_length(128)'],
        'intro'        => ['required', 'min_length(100)', 'max_length(1500)'],
    ],

    'account_location' => [
        'geo_lat'      => ['max_length(20)'],
        'geo_lon'      => ['max_length(20)'],
        'country_id'   => ['required', 'exists("tomos_countries", "id")'],
        'city'         => ['required', 'min_length(3)', 'max_length(64)'],
    ],

    'account_experience' => [
        'exp_company'  => ['required', 'min_length(3)', 'max_length(128)'],
        'exp_position' => ['required', 'min_length(3)', 'max_length(128)'],
        'exp_city'     => ['required', 'min_length(3)', 'max_length(128)'],
        'exp_text'     => ['optional', 'max_length(500)'],
        'exp_current'  => ['in(["0","1"])'],
        'exp_from'     => ['required', 'date("Y/m")'],
        'exp_to'       => ['optional', 'date("Y/m")'],
    ],

    'account_education' => [
        'edu_school'   => ['required', 'min_length(3)', 'max_length(128)'],
        'edu_degree'   => ['required', 'min_length(3)', 'max_length(128)'],
        'edu_sphere'   => ['required', 'min_length(3)', 'max_length(128)'],
        'edu_city'     => ['required', 'min_length(3)', 'max_length(128)'],
        'edu_from'     => ['required', 'date("Y")'],
        'edu_to'       => ['required', 'date("Y")'],
    ],

    'account_password' => [
        'old_password' => ['required'],
        'new_password' => ['required', 'min_length(6)', 'max_length(32)', 'match("new_confirmation")'],
    ],

    'account_setting'  => [
        'can_view'         => ['in(["everyone","group-members","my-followers","only-me"])'],
        'can_tag'          => ['in(["everyone","group-members","my-followers"])'],
        'show_followers'   => ['in(["0","1"])'],
        'show_email'       => ['in(["0","1"])'],
        'show_phone'       => ['in(["0","1"])'],
        'show_experiences' => ['in(["0","1"])'],
        'show_educations'  => ['in(["0","1"])'],
        'show_age'         => ['in(["0","1"])'],
        'allow_follow'     => ['in(["0","1"])'],
    ],

    'account_avatar'   => [
        'avatar'       => ['required', 'is_uploaded', 'mimetype(["image/jpeg"])', 'exact_dimensions(200, 200)'],
    ],

    'account_cover'    => [
        'cover'        => ['required', 'is_uploaded', 'mimetype(["image/jpeg"])', 'exact_dimensions(1920, 443)'],
    ],

];
