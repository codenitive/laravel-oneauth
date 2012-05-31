<?php

return array(
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
		
		'facebook' => array(
			'id'     => '',
			'secret' => '',
			'scope'  => 'email,offline_access',
		),

		'flickr' => array(
			'key'    => '',
			'secret' => '',
		),

		'foursquare' => array(
			'id'     => '',
			'secret' => '',
		),

		'github' => array(
			'id'     => '',
			'secret' => '',
		),

		'google' => array(
			'id'     => '',
			'secret' => '',
		),

		'instagram' => array(
			'id'     => '',
			'secret' => '',
		),

		'linkedin' => array(
			'key'    => '',
			'secret' => '',
		),
		
		'tumblr' => array(
			'key'    => '',
			'secret' => '',
		),

		'twitter' => array(
			'key'    => '',
			'secret' => '',
		),

		'youtube' => array(
			'key'   => '',
			'scope' => 'http://gdata.youtube.com',
		),
		
		'openid' => array (
			'identifier_form_name' => 'openid_identifier',
			'ax_required' => array('contact/email', 'namePerson/first', 'namePerson/last'),
			'ax_optional' => array('namePerson/friendly', 'birthDate', 'person/gender', 'contact/country/home'),
		),
	
	),
);