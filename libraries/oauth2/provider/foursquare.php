<?php namespace OneAuth\OAuth2\Provider;

/**
 * Ported from FuelPHP \OAuth2 package
 *
 * @package    FuelPHP/OAuth2
 * @category   Provider
 * @author     Phil Sturgeon
 * @copyright  (c) 2012 HappyNinjas Ltd
 * @license    http://philsturgeon.co.uk/code/dbad-license
 */

use OneAuth\OAuth2\Provider as OAuth2_Provider,
	OneAuth\OAuth2\Token\Access as Token_Access;

class Foursquare extends OAuth2_Provider
{
	/**
	 * @var  string  the method to use when requesting tokens
	 */
	public $method = 'POST';

	public function url_authorize()
	{
		return 'https://foursquare.com/oauth2/authenticate';
	}

	public function url_access_token()
	{
		return 'https://foursquare.com/oauth2/access_token';
	}

	public function get_user_info(Token_Access $token)
	{
		$url = 'https://api.foursquare.com/v2/users/self?'.http_build_query(array(
			'oauth_token' => $token->access_token,
		));

		$response = json_decode(file_get_contents($url));

		$user = $response->response->user;

		// Create a response from the request
		return array(
			'uid'      => $user->id,
			'name'     => sprintf('%s %s', $user->firstName, $user->lastName),
			'email'    => $user->contact->email,
			'image'    => $user->photo,
			'location' => $user->homeCity,
		);
	}
}