<?php

Autoloader::namespaces(array(
	'OneAuth\\Auth'   => Bundle::path('oneauth').'libraries'.DS.'auth',
	'OneAuth\\OAuth'  => Bundle::path('oneauth').'libraries'.DS.'oauth',
	'OneAuth\\OAuth2' => Bundle::path('oneauth').'libraries'.DS.'oauth2',
));

Event::listen('oneauth.logged', function ($client, $user_data)
{
	// do something when client logged in.
});

Event::listen('oneauth.sync', function ($user_id)
{
	return OneAuth\Auth\Core::sync($user_id);
});