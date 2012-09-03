OneAuth by default utilize `Laravel\Auth` for user authentication, however if you need to use other authentication 
bundle such as **Sentry**, following snippet shows exactly all you need.

	<?php

	/*
	|--------------------------------------------------------------------------
	| OneAuth IoC
	|--------------------------------------------------------------------------
	|
	| Register Auth adapter as IoC, allow it to be replaced by any Authentication
	| bundle that doesn't use Laravel\Auth\Drivers
	 */
	// Check whether current user is logged-in to the system or a guest
	IoC::register('oneauth.driver: auth.check', function ()
	{
		return Sentry::check();
	});


	// Get logged in user, if the user doesn't logged in yet, return null
	IoC::register('oneauth.driver: auth.user', function ()
	{
		return Sentry::user();
	});

	// Login the user by users.id
	IoC::register('oneauth.driver: auth.login', function ($user_id)
	{
		return Sentry::force_login($user_id);
	});