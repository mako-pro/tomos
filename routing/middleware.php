<?php

use app\routing\middleware\tomos\AjaxMiddleware;

$dispatcher->registerMiddleware('ajax', AjaxMiddleware::class, 1);
