<?php

/*
|--------------------------------------------------------------------------
| Integration with Orchestra
|--------------------------------------------------------------------------
|
| Overwrite default configuration
 */

Config::set('oneauth::urls', array(
	'registration' => handles('orchestra::register'),
	'login'        => handles('orchestra::login'),
	'callback'     => handles('oneauth::connect/callback'),
	
	'registered'   => handles('orchestra'),
	'logged_in'    => handles('orchestra'),
));

/*
|--------------------------------------------------------------------------
| Integration with Orchestra
|--------------------------------------------------------------------------
|
| Map controller routing for OneAuth
 */

Route::controller(array('oneauth::connect'));

/*
|--------------------------------------------------------------------------
| Integration with Orchestra
|--------------------------------------------------------------------------
|
| Add on logged-in integration between OneAuth and Orchestra
 */

Event::listen('orchestra.logged.in', function() 
{
	$user = Auth::user();
	Event::fire('oneauth.sync', array($user->id));
});
