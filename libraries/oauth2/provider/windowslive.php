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

class Windowslive extends OAuth2_Provider
{
	/**
	 * @var  string  provider name
	 */
	public $name = 'windowslive';

	protected $method = 'POST_QUERY';

	public function __construct(array $options = array())
	{
		// Now make sure we have the default scope to get user data
		$options['scope'] = array_merge(

			// We need this default feed to get the authenticated users
			// basic information
			array('wl.basic', 'wl.emails'),

			// And take either a string and array it, or empty array to
			// merge into
			(array) array_get($options, 'scope', array())
		);

		parent::__construct($options);
	}

	// authorise url
	public function url_authorize()
	{
		// return the authorise URL
		return 'https://login.live.com/oauth20_authorize.srf';
	}

	// access token url
	public function url_access_token()
	{
		// return the access token URL
		return 'https://login.live.com/oauth20_token.srf';
	}

	// get basic user information
	/********************************
	** this can be extended through the
	** use of scopes, check out the document at
	** http://msdn.microsoft.com/en-gb/library/hh243648.aspx#user
	*********************************/
	public function get_user_info(Token_Access $token)
	{
		// define the get user information token
		$request = Request::make('resource', 'GET', 'https://apis.live.net/v5.0/me', array(
			'access_token' => $token->access_token,
		));

		// perform network request
		$user = json_decode($request->execute());

		// create a response from the request and return it
		return array(
			'uid'      => $user->id,
			'name'     => $user->name,
			'email'    => isset($user->emails->preferred) ? $user->emails->preferred : null,
			'nickname' => \Str::slug($user->name, '-'),
			'locale'   => $user->locale,
			'urls'     => array(
				'windowslive' => $user->link
			),
		);
	}
}
