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

Orchestra\Extension\Config::map('oneauth', array(
	'basecamp_id'       => 'oneauth::api.providers.basecamp.id',
	'basecamp_secret'   => 'oneauth::api.providers.basecamp.secret',

	'dropbox_key'       => 'oneauth::api.providers.dropbox.key',
	'dropbox_secret'    => 'oneauth::api.providers.dropbox.secret',

	'facebook_id'       => 'oneauth::api.providers.facebook.id',
	'facebook_secret'   => 'oneauth::api.providers.facebook.secret',
	'facebook_scope'    => 'oneauth::api.providers.facebook.scope',

	'flickr_key'        => 'oneauth::api.providers.flickr.key',
	'flickr_secret'     => 'oneauth::api.providers.flickr.secret',

	'foursquare_id'     => 'oneauth::api.providers.foursquare.id',
	'foursquare_secret' => 'oneauth::api.providers.foursquare.secret',

	'github_id'         => 'oneauth::api.providers.github.id',
	'github_secret'     => 'oneauth::api.providers.github.secret',

	'google_id'         => 'oneauth::api.providers.google.id',
	'google_secret'     => 'oneauth::api.providers.google.secret',

	'instagram_id'      => 'oneauth::api.providers.instagram.id',
	'instagram_secret'  => 'oneauth::api.providers.instagram.secret',

	'linkedin_key'      => 'oneauth::api.providers.linkedin.key',
	'linkedin_secret'   => 'oneauth::api.providers.linkedin.secret',

	'paypal_id'         => 'oneauth::api.providers.paypal.id',
	'paypal_secret'     => 'oneauth::api.providers.paypal.secret',

	'soundcloud_id'     => 'oneauth::api.providers.soundcloud.id',
	'soundcloud_secret' => 'oneauth::api.providers.soundcloud.secret',

	'tumblr_key'        => 'oneauth::api.providers.tumblr.key',
	'tumblr_secret'     => 'oneauth::api.providers.tumblr.secret',

	'twitter_key'       => 'oneauth::api.providers.twitter.key',
	'twitter_secret'    => 'oneauth::api.providers.twitter.secret',

	'vimeo_key'         => 'oneauth::api.providers.vimeo.key',
	'vimeo_secret'      => 'oneauth::api.providers.vimeo.secret',

	'windowlive_id'     => 'oneauth::api.providers.windowlive.id',
	'windowlive_secret' => 'oneauth::api.providers.windowlive.secret',

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

include_once Bundle::path('oneauth').'orchestra'.DS.'configure'.EXT;