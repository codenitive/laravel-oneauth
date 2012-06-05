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

class Facebook extends OAuth2_Provider
{  
	/**
	 * @var  string  provider name
	 */
	public $name = 'facebook';

	public $scope = array('offline_access', 'email', 'read_stream');

	public function url_authorize()
	{
		return 'https://www.facebook.com/dialog/oauth';
	}

	public function url_access_token()
	{
		return 'https://graph.facebook.com/oauth/access_token';
	}

	public function get_user_info(Token_Access $token)
	{
		$request = Request::make('resource', 'GET', 'https://graph.facebook.com/me', array(
			'access_token' => $token->access_token,
		));
		
		$user = json_decode($request->execute());

		// Create a response from the request
		return array(
			'uid'      => $user->id,
			'name'     => $user->name,
			'nickname' => isset($user->username) ? $user->username : null,
			'email'    => isset($user->email) ? $user->email : null,
			'image'    => 'https://graph.facebook.com/me/picture?type=normal&access_token='.$token->access_token,
			'urls'     => array(
			  'facebook' => $user->link,
			),
		);
	}
}