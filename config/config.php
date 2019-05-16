<?php

return [

    // Routes options
    'prefix'   => 'tomos',
    'register' => true,
    'reset'    => true,
    'verify'   => true,

    // Groups
    'default_group' => 'members',

    // Uploads directory
    'uploads_path'  => str_replace('app', 'public', MAKO_APPLICATION_PATH) . '/placer/tomos/uploads/',

];
