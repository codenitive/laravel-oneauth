<?php

/*
|--------------------------------------------------------------------------
| OneAuth Library
|--------------------------------------------------------------------------
|
| Map OneAuth Library using PSR-0 standard namespace. 
 */
Autoloader::namespaces(array(
	'OneAuth\\Auth'   => Bundle::path('oneauth').'libraries'.DS.'auth',
	'OneAuth\\OAuth'  => Bundle::path('oneauth').'libraries'.DS.'oauth',
	'OneAuth\\OAuth2' => Bundle::path('oneauth').'libraries'.DS.'oauth2',
));

/*
|--------------------------------------------------------------------------
| OneAuth Events Listener
|--------------------------------------------------------------------------
|
| Lets listen to when OneAuth logged a user using any of the supported 
| providers. 
|
| OneAuth also listen to when user actually logged in to Laravel
 */ 
Event::listen('oneauth.logged', function ($client, $user_data)
{
	// if user already logged in, don't do anything
	if (\Auth::check()) return ;

	// OneAuth should login the user if user exist and is not logged in
	if (is_numeric($client->user_id) and $client->user_id > 0)
	{
		\Auth::login($client->user_id);
	}
});

Event::listen('oneauth.sync', function ($user_id)
{
	return OneAuth\Auth\Core::sync($user_id);
});