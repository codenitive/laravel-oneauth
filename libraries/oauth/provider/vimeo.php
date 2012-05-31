<?php namespace OneAuth\OAuth\Provider;

/**
 * OAuth Vimeo Provider
 *
 * Documents for implementing Vimeo OAuth can be found at
 * <http://vimeo.com/api/docs/oauth>.
 *
 * [!!] This class does not implement the Vimeo API. It is only an
 * implementation of standard OAuth with Vimeo as the service provider.
 *
 * @package    OAuth
 * @author     Fuel Development Team
 */

use OneAuth\OAuth\Provider as OAuth_Provider,
	OneAuth\OAuth\Consumer,
	OneAuth\OAuth\Request,
	OneAuth\OAuth\Token;

class Vimeo extends OAuth_Provider 
{
	public $name = 'vimeo';

	public function url_request_token()
	{
		return 'http://vimeo.com/oauth/request_token';
	}

	public function url_authorize()
	{
		return 'http://vimeo.com/oauth/authorize';
	}

	public function url_access_token()
	{
		return 'http://vimeo.com/oauth/access_token';
	}
	
	public function get_user_info(Token $token, Consumer $consumer)
	{		
		// Create a new GET request with the required parameters
		$request = Request::make('resource', 'GET', 'http://vimeo.com/api/rest/v2/', array(
			'method'             => 'vimeo.people.getInfo',
			'oauth_consumer_key' => $consumer->key,
			'oauth_token'        => $token->access_token,
			'format'             => 'json',
		));

		// Sign the request using the consumer and token
		$request->sign($this->signature, $consumer, $token);

		$response      = json_decode($request->execute());
		$user          = $response->person; 
		
		$profile_image = end($user->portraits->portrait);
		$url           = current($user->url);
		
		// Create a response from the request
		return array(
			'uid'         => $user->id,
			'nickname'    => $user->username,
			'name'        => $user->display_name ?: $user->username,
			'location'    => $user->location,
			'image'       => $profile_image->_content,
			'description' => $user->bio,
			'urls'        => array(
				'website' => $url,
				'vimeo'   => $user->profileurl,
			),
		);
	}
}