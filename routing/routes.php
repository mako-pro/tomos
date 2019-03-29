<?php

$routes->group(['namespace' => 'placer\tomos\controllers'], function ($routes)
{
	$routes->get('/tomos', 'WelcomeController::index');
});
