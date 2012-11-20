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

	/**
	 * Get the authorization URL for the request token.
	 *
	 *     Response::redirect($provider->authorize_url($token));
	 *
	 * @param   Token_Request  token
	 * @param   array                additional request parameters
	 * @return  string
	 */
	public function authorize_url(Token\Request $token, array $params = null)
	{
		// Create a new GET request for a request token with the required parameters
		$request = Request::make('authorize', 'GET', $this->url_authorize(), array(
			'oauth_token' => $token->access_token,
			'perms'       => 'read',
		));

		if ($params)
		{
			// Load user parameters
			$request->params($params);
		}

		return $request->as_url();
	}

	/**
	 * Exchange the request token for an access token.
	 *
	 *     $token = $provider->access_token($consumer, $token);
	 *
	 * @param   Consumer       consumer
	 * @param   Token_Request  token
	 * @param   array                additional request parameters
	 * @return  Token_Access
	 */
	public function access_token(Token\Request $token, Consumer $consumer, array $params = null)
	{
		// Create a new GET request for a request token with the required
		// parameters
		$request = Request::make('access', 'GET', $this->url_access_token(), array(
			'oauth_consumer_key' => $consumer->key,
			'oauth_access_token' => $token->access_token,
			'oauth_verifier'     => $token->verifier,
		));

		if ($params)
		{
			// Load user parameters
			$request->params($params);
		}

		// Sign the request using only the consumer, no token is available
		// yet
		$request->sign($this->signature, $consumer, $token);

		// Create a response from the request
		$response = $request->execute();

		// Store this token somewhere useful
		return Token::make('access', array(
			'access_token' => $response->param('oauth_token'),
			'secret'       => $response->param('oauth_token_secret'),
			'uid'          => $response->param($this->uid_key) ?: Input::get($this->uid_key),
		));
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
