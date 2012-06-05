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
	OneAuth\OAuth2\Request,
	OneAuth\OAuth2\Token\Access as Token_Access;

class Soundcloud extends OAuth2_Provider
{
	/**
	 * @var  string  provider name
	 */
	public $name = 'soundcloud';

	/**
	 * @var  string  the method to use when requesting tokens
	 */
	protected $method = 'POST';

	public function url_authorize()
	{
		return 'https://soundcloud.com/connect';
	}

	public function url_access_token()
	{
		return 'https://api.soundcloud.com/oauth2/token';
	}

	public function get_user_info(Token_Access $token)
	{
		$request = Request::make('resource', 'GET', 'https://api.soundcloud.com/me.json', array(
			'oauth_token' => $token->access_token,
		));

		$user = json_decode($request->execute());

		// Create a response from the request
		return array(
			'uid'         => $user->id,
			'nickname'    => $user->username,
			'name'        => $user->full_name,
			'location'    => $user->country.' ,'.$user->country,
			'description' => $user->description,
			'image'       => $user->avatar_url,
			'urls'        => array(
				'myspace' => $user->myspace_name,
				'website' => $user->website,
			),
		);
	}
}