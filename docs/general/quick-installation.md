# Installation

## Installation with Laravel Artisan

	php artisan bundle:install oneauth
	
## Bundle Registration

	'oneauth' => array('auto' => true),

## Run the migration

	php artisan migrate
	
## Configuration

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

### Update URLs callback configuration

Since we're using `Connect_Controller` in this example, we need to update the `oneauth/config/urls.php`.

	<?php

	return array(
		...
		
		'callback'     => 'connect/callback',
	);
