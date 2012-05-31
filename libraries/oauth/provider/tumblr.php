<?php namespace OneAuth\OAuth\Provider;

/**
 * OAuth Tumblr Provider
 *
 * Documents for implementing Tumblr OAuth can be found at
 * <http://tumblr.com/api>.
 *
 * [!!] This class does not implement the Tumblr API. It is only an
 * implementation of standard OAuth with Tumblr as the service provider.
 *
 * @package    OAuth
 * @category   Provider
 * @author     Fuel Development Team
 */

use OneAuth\OAuth\Provider as OAuth_Provider,
	OneAuth\OAuth\Consumer,
	OneAuth\OAuth\Request,
	OneAuth\OAuth\Token;

class Tumblr extends OAuth_Provider 
{
	public $name = 'tumblr';

	public function url_request_token()
	{
		return 'http://www.tumblr.com/oauth/request_token';
	}

	public function url_authorize()
	{
		return 'http://www.tumblr.com/oauth/authorize';
	}

	public function url_access_token()
	{
		return 'http://www.tumblr.com/oauth/access_token';
	}
	
	public function get_user_info(Token $token, Consumer $consumer)
	{
		// Create a new GET request with the required parameters
		$request = Request::make('resource', 'GET', 'http://api.tumblr.com/v2/user/info', array(
			'oauth_consumer_key' => $consumer->key,
			'oauth_token'        => $token->access_token,
		));

		// Sign the request using the consumer and token
		$request->sign($this->signature, $consumer, $token);

		$response = json_decode($request->execute());
		
		$status   = current($response);
		$response = next($response);
		$user     = $response->user;
		
		// Create a response from the request
		return array(
			'uid'                 => $user->name,    // Tumblr doesn't provide a unique key other than name
			'name'                => $user->name,
			'likes'               => $user->likes,
			'following'           => $user->following,
			'default_post_format' => $user->default_post_format,
		);
	}
}