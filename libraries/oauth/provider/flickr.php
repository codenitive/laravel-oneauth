<?php namespace OneAuth\OAuth\Provider;

/**
 * OAuth Flickr Provider
 *
 * Documents for implementing Flickr OAuth can be found at
 * <http://www.flickr.com/services/api/>.
 *
 * [!!] This class does not implement the Flickr API. It is only an
 * implementation of standard OAuth with Flickr as the service provider.
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

class Flickr extends OAuth_Provider 
{
	public $name = 'flickr';

	public function url_request_token()
	{
		return 'http://www.flickr.com/services/oauth/request_token';
	}

	public function url_authorize()
	{
		return 'http://www.flickr.com/services/oauth/authorize';
	}

	public function url_access_token()
	{
		return 'http://www.flickr.com/services/oauth/access_token';
	}
	
	public function get_user_info(Token $token, Consumer $consumer)
	{
		// Create a new GET request with the required parameters
		$request = Request::make('resource', 'GET', 'http://api.flickr.com/services/rest', array(
			'oauth_consumer_key' => $consumer->key,
			'oauth_token'        => $token->access_token,
			'nojsoncallback'     => 1,
			'format'             => 'json',
			'method'             => 'flickr.test.login',
		));

		// Sign the request using the consumer and token
		$request->sign($this->signature, $consumer, $token);

		$response = json_decode($request->execute(), true);

		// Create a response from the request
		return array(
			'uid'      => array_get($response, 'user.id'),
			'name'     => array_get($response, 'user.username._content'),
			'nickname' => array_get($response, 'user.username._content'),
		);
	}
}