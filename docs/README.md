# OneAuth Bundle

OAuth, OAuth2 and OpenID Auth bundle for Laravel

## Supported Provider

* OAuth
	- Dropbox
	- Flickr
	- LinkedIn
	- Twitter
	- Vimeo
* OAuth2
	- Facebook
	- Foursquare
	- Github
	- Google
	- Instagram
	- Paypal
	- SoundCloud
	- WindowsLice
* OpenID *(To be added)*

## Installation

### Installation with Laravel Artisan

	php artisan bundle:install oneauth
	
### Bundle Registration

	'oneauth' => array('auto' => true),

### Run the migration

	php artisan migrate
	
### Configuration

First update `oneauth/config/api.php` with application key and secret from respective provider.

	/**
	 * Providers
	 * 
	 * Providers such as Facebook, Twitter, etc all use different Strategies such as oAuth, oAuth2, etc.
	 * oAuth takes a key and a secret, oAuth2 takes a (client) id and a secret, optionally a scope.
	 */
	'providers' => array(

		'dropbox' => array(
			'key'    => '',
			'secret' => '',
		),
		
		…

Once you're done, the only thing you need to do is create a controller

	<?php
	
	class Connect_Controller extends OneAuth\Auth\Controller {}
	
	// File: application/controllers/connect.php
	
That's all you need to do. Remember to add `Route::controller(Controller::detect());` if you haven't done so.

#### Update URLs callback configuration

Since we're using `Connect_Controller` in this example, we need to update the `oneauth/config/urls.php`.

	<?php

	return array(
		...
		
		'callback'     => 'connect/callback',
	);

## Events

OneAuth come with two Events, one during login with OAuth/OAuth2 and another to synchronize user login/register with OAuth/OAuth2 database.

### oneauth.logged

This event will be triggered once user logged in with OAuth or OAuth2, this would be good if you need to get extra information from providers including name, personal information or even profile picture.

	/**
	 * @param   OneAuth\Auth\Client   $client       (instanceof Eloquent)
	 * @param   array                 $user_data    users' data from provider
	Event::listen('oauth.logged', function ($client, $user_data)
	{
		// do something cool with those information
	});
	
### oneauth.sync

This event should be fired when the user actually create an account in your application. For example, you may have `User` Eloquent Model and authenticate using `Auth`.
	
	$login = array('username' => Input::get('username'), 'password' => Input::get('password'));
	
	if (Auth::attempt($login))
	{
		// get logged user id.
		$user_id = Auth::user()->id;
		
		// Synced it with oneauth, this will create a relationship between
		// `oneauth_clients` table with `users` table.
		Event::fire('oneauth.sync', array($user_id));
	} 