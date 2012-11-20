<?php namespace OneAuth\OAuth2\Provider;

/**
 * Basecamp oAuth2
 *
 * @package    OneAuth
 * @category   Provider
 * @author     Chris Fidao
 * @license    http://opensource.org/licenses/mit-license.php
 */

use OneAuth\OAuth2\Provider as OAuth2_Provider,
	OneAuth\OAuth2\Request,
	OneAuth\OAuth2\Token\Access as Token_Access,
	OneAuth\OAuth2\Token,
	\Redirect, \Session;

class Basecamp extends OAuth2_Provider
{
	//POST for Basecamp
	protected $method = 'POST';

	/**
	 * @var  string  provider name
	 */
	public $name = 'basecamp';

	public function url_authorize()
	{
		return 'https://launchpad.37signals.com/authorization/new';
	}

	public function url_access_token()
	{
		return 'https://launchpad.37signals.com/authorization/token';
	}

	public function get_user_info(Token_Access $token)
	{

		$request = Request::make('resource', 'GET', 'https://launchpad.37signals.com/authorization.json', array(
			'access_token' => $token->access_token,
		));

		$user = json_decode($request->execute());

		/**
		* Create a response from the request Basecamp Authorization response
		* has extra requirements and doesn't give some of the meta
		* information below. Image is available via the me.json call.
		*/
		return array(
			'uid'      => $user->identity->id,
			'name'     => $user->identity->first_name .' '.$user->identity->last_name,
			'nickname' => null,
			'email'    => isset($user->identity->email_address) ? $user->identity->email_address : null,
			'image'    => '',
			'urls'     => array(),
		);
	}

	//Override this method for basecamp-specific items
	public function authorize($options = array())
	{
		$state = md5(uniqid(rand(), TRUE));
		Session::put('state', $state);

		$url = $this->url_authorize().'?'.http_build_query(array(
			'type'				=> 'web_server',
			'client_id' 		=> $this->client_id,
			'redirect_uri' 		=> array_get($options, 'redirect_uri', $this->redirect_uri),
			'state' 			=> $state,
			'response_type' 	=> 'code',
		));

		return Redirect::to($url);
	}

	//Override this method for Basecamp-specific items
	public function access($code, $options = array())
	{
		$params = array(
			'client_id' 	=> $this->client_id,
			'client_secret' => $this->client_secret,
			'grant_type' 	=> array_get($options, 'grant_type', 'authorization_code'),
			'type'			=> 'web_server'
		);

		switch ($params['grant_type'])
		{
			case 'authorization_code':
				$params['code']         = $code;
				$params['redirect_uri'] = array_get($options, 'redirect_uri', $this->redirect_uri);
			break;

			case 'refresh_token':
				$params['refresh_token'] = $code;
			break;
		}

		$response = null;
		$url      = $this->url_access_token();

		$request  = Request::make('access', $this->method, $url, $params);
		$response = $request->execute();

		if (isset($response->error))
		{
			throw new Exception($response);
		}

		return Token::make('access', $response->params);
	}
}
