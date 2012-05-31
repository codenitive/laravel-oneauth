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
	OneAuth\OAuth2\Token\Access as Token_Access,
	OneAuth\OAuth2\Exception;

class Google extends OAuth2_Provider
{
	/**
	 * @var  string  provider name
	 */
	public $name = 'google';

	/**
	 * @var  string  the method to use when requesting tokens
	 */
	public $method = 'POST';

	/**
	 * @var  string  scope separator, most use "," but some like Google are spaces
	 */
	public $scope_seperator = ' ';

	public function url_authorize()
	{
		return 'https://accounts.google.com/o/oauth2/auth';
	}

	public function url_access_token()
	{
		return 'https://accounts.google.com/o/oauth2/token';
	}

	public function authorize($options = array())
	{
		$state = md5(uniqid(rand(), TRUE));
		\Session::put('state', $state);
		
		$params = array(
			'client_id'       => $this->client_id,
			'redirect_uri'    => array_get($options, 'redirect_uri', $this->redirect_uri),
			'state'           => $state,
			'scope'           => is_array($this->scope) ? implode($this->scope_seperator, $this->scope) : $this->scope,
			'response_type'   => 'code',
			'access_type'     => 'offline',
			'approval_prompt' => 'force',
		);

		$url = $this->url_authorize().'?'.http_build_query($params);
		
		return \Redirect::to($url);
	}

	public function __construct(array $options = array())
	{
		// Now make sure we have the default scope to get user data
		$options['scope'] = array_merge(
			
			// We need this default feed to get the authenticated users basic information
			// array('https://www.googleapis.com/auth/plus.me'),
			array('https://www.google.com/m8/feeds'),
			
			// And take either a string and array it, or empty array to merge into
			(array) array_get($options, 'scope', array())
		);
		
		parent::__construct($options);
	}

	/*
	* Get access to the API
	*
	* @param	string	The access code
	* @return	object	Success or failure along with the response details
	*/	
	public function access($code, $options = array())
	{
		if ($code === null)
		{
			throw new Exception(array('message' => 'Expected Authorization Code from '.ucfirst($this->name).' is missing'));
		}

		return parent::access($code, $options);
	}

	public function get_user_info(Token_Access $token)
	{
		$url = 'https://www.google.com/m8/feeds/contacts/default/full?max-results=1&alt=json&'.http_build_query(array(
			'access_token' => $token->access_token,
		));
		
		$response = json_decode(file_get_contents($url), true);
		
		// Fetch data parts
		$email = array_get($response, 'feed.id.$t');
		$name  = array_get($response, 'feed.author.0.name.$t');
		$name  == '(unknown)' and $name = $email;
		
		return array(
			'uid'         => $email,
			'nickname'    => \Str::slug($name, '-'),
			'name'        => $name,
			'email'       => $email,
			'location'    => null,
			'image'       => null,
			'description' => null,
			'urls'        => array(),
		);
	}
}