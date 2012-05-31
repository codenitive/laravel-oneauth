<?php namespace OneAuth\OAuth\Provider;

/**
 * OAuth LinkedIn Provider
 *
 * Documents for implementing LinkedIn OAuth can be found at
 * <http://developer.linkedin.com/>.
 *
 * [!!] This class does not implement the LinkedIn API. It is only an
 * implementation of standard OAuth with LinkedIn as the service provider.
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

class Linkedin extends OAuth_Provider 
{
	public $name = 'linkedin';

	public function url_request_token()
	{
		return 'https://api.linkedin.com/uas/oauth/requestToken';
	}

	public function url_authorize()
	{
		return 'https://api.linkedin.com/uas/oauth/authorize';
	}

	public function url_access_token()
	{
		return 'https://api.linkedin.com/uas/oauth/accessToken';
	}
	
	public function get_user_info(Consumer $consumer, Token $token)
	{
		// Create a new GET request with the required parameters
		$url     = 'https://api.linkedin.com/v1/people/~:(id,first-name,last-name,headline,member-url-resources,picture-url,location,public-profile-url)';
		$request = Request::make('resource', 'GET', $url, array(
			'oauth_consumer_key' => $consumer->key,
			'oauth_token'        => $token->access_token,
		));

		// Sign the request using the consumer and token
		$request->sign($this->signature, $consumer, $token);
		
		$user = $this->parse($request->execute(), 'xml')->to_array();
		
		// Create a response from the request
		return array(
			'uid'         => $user['id'],
			'name'        => $user['first-name'].' '.$user['last-name'],
			'nickname'    => end(explode('/', $user['public-profile-url'])),
			'description' => $user['headline'],
			'location'    => array_get($user, 'location.name'),
			'urls'        => array(
			  'Linked In' => $user['public-profile-url'],
			),
		);
	}

	protected function parse($string)
	{
		$data = is_string($string) ? simplexml_load_string($string, 'SimpleXMLElement', LIBXML_NOCDATA) : $string;
		$arr  = array();

		// Convert all objects SimpleXMLElement to array recursively
		foreach ((array)$data as $key => $val)
		{
			$arr[$key] = (is_array($val) or is_object($val)) ? $this->_from_xml($val) : $val;
		}

		return $arr;
	}
}