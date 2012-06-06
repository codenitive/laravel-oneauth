<?php

Autoloader::namespaces(array(
	'OneAuth\\Auth'   => Bundle::path('oneauth').'libraries'.DS.'auth',
	'OneAuth\\OAuth'  => Bundle::path('oneauth').'libraries'.DS.'oauth',
	'OneAuth\\OAuth2' => Bundle::path('oneauth').'libraries'.DS.'oauth2',
));

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