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

class Instagram extends OAuth2_Provider
{
	/**
	 * @var  string  provider name
	 */
	public $name = 'instagram';

	/**
	 * @var  string  scope separator, most use "," but some like Google are
	 *               spaces
	 */
	public $scope_seperator = '+';

	/**
	 * @var  string  the method to use when requesting tokens
	 */
	public $method = 'POST';

	public function url_authorize()
	{
		return 'https://api.instagram.com/oauth/authorize';
	}

	public function url_access_token()
	{
		return 'https://api.instagram.com/oauth/access_token';
	}

	public function __construct(array $options = array())
	{
		// Now make sure we have the default scope to get user data
		$options['scope'] = array_merge(

			// We need this default feed to get the authenticated users
			// basic information
			array('basic'),

			// And take either a string and array it, or empty array to
			// merge into
			(array) array_get($options, 'scope', array())
		);

		parent::__construct($options);
	}

	public function get_user_info(Token_Access $token)
	{
		$user = $token->user;

		// Create a response from the request
		return array(
			'uid'      => $user->id,
			'nickname' => $user->username,
			'name'     => $user->full_name,
			'image'    => $user->profile_picture,
			'urls'     => array(
				'website' => $user->website,
			),
		);
	}
}
