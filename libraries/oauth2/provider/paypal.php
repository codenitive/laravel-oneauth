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

class Paypal extends OAuth2_Provider
{
	/**
	 * @var  string  provider name
	 */
	public $name = 'paypal';

	/**
	 * @var  string  default scope (useful if a scope is required for user info)
	 */
	protected $scope = 'https://identity.x.com/xidentity/resources/profile/me';

	/**
	 * @var  string  the method to use when requesting tokens
	 */
	protected $method = 'POST_QUERY';

	public function url_authorize()
	{
		return 'https://identity.x.com/xidentity/resources/authorize';
	}

	public function url_access_token()
	{
		return 'https://identity.x.com/xidentity/oauthtokenservice';
	}

	public function get_user_info(Token_Access $token)
	{
		$request  = Request::make('resource', 'GET', 'https://identity.x.com/xidentity/resources/profile/me', array(
			'oauth_token' => $token->access_token
		));

		$response = json_decode($request->execute());

		$user     = $response->identity;

		return array(
			'uid'         => $user->userId,
			'nickname'    => \Str::slug($user->fullName, '-'),
			'name'        => $user->fullName,
			'first_name'  => $user->firstName,
			'last_name'   => $user->lastName,
			'email'       => $user->emails[0],
			'location'    => isset($user->addresses) ? $user->addresses[0] : '',
			'image'       => null,
			'description' => null,
			'urls'        => array(
				'paypal' => null
			)
		);
	}
}