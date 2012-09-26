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

class Github extends OAuth2_Provider
{
	/**
	 * @var  string  provider name
	 */
	public $name = 'github';

	public function url_authorize()
	{
		return 'https://github.com/login/oauth/authorize';
	}

	public function url_access_token()
	{
		return 'https://github.com/login/oauth/access_token';
	}

	public function get_user_info(Token_Access $token)
	{
		$url = 'https://api.github.com/user?'.http_build_query(array(
			'access_token' => $token->access_token,
		));

		$request = Request::make('resource', 'GET', 'https://api.github.com/user', array(
			'access_token' => $token->access_token,
		));

		$user = json_decode($request->execute());

		// Create a response from the request
		return array(
			'uid'      => $user->id,
			'nickname' => $user->login,
			'name'     => isset($user->name) ? $user->name : null,
			'email'    => isset($user->email) ? $user->email : null,
			'urls'     => array(
				'github' => 'http://github.com/'.$user->login,
				'blog'   => isset($user->blog) ? $user->blog : null,
			),
		);
	}
}