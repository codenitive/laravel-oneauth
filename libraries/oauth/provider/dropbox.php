<?php namespace OneAuth\OAuth\Provider;

/**
 * OAuth Dropbox Provider
 *
 * Documents for implementing Dropbox OAuth can be found at
 * <https://www.dropbox.com/developers>.
 *
 * [!!] This class does not implement the Dropbox API. It is only an
 * implementation of standard OAuth with Dropbox as the service provider.
 *
 * @package    Kohana/OAuth
 * @category   Provider
 * @author     Kohana Team
 * @copyright  (c) 2010 Kohana Team
 * @license    http://kohanaframework.org/license
 * @since      3.0.7
 */

use OneAuth\OAuth\Provider as OAuth_Provider,
	OneAuth\OAuth\Consumer,
	OneAuth\OAuth\Request,
	OneAuth\OAuth\Token;

class Dropbox extends OAuth_Provider 
{
	public $name = 'dropbox';

	public function url_request_token()
	{
		return 'https://api.dropbox.com/1/oauth/request_token';
	}

	public function url_authorize()
	{
		return 'http://www.dropbox.com/1/oauth/authorize';
	}

	public function url_access_token()
	{
		return 'https://api.dropbox.com/1/oauth/access_token';
	}
	
	public function get_user_info(Token $token, Consumer $consumer)
	{
		// Create a new GET request with the required parameters
		$request = Request::make('resource', 'GET', 'https://api.dropbox.com/1/account/info', array(
			'oauth_consumer_key' => $consumer->key,
			'oauth_token'        => $token->access_token,
		));

		// Sign the request using the consumer and token
		$request->sign($this->signature, $consumer, $token);

		$user = json_decode($request->execute());
		
		// Create a response from the request
		return array(
			'uid'      => $token->uid,
			'name'     => $user->display_name,
			'location' => $user->country,
		);
	}
}