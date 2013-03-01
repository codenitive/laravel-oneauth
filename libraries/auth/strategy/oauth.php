<?php namespace OneAuth\Auth\Strategy;

/**
 * Auth Class taken from NinjAuth Package for FuelPHP
 *
 * @package     NinjAuth
 * @author      Phil Sturgeon <https://github.com/philsturgeon>
 */

use OneAuth\Auth\Strategy as Auth_Strategy,
	OneAuth\OAuth\Consumer,
	OneAuth\OAuth\Provider;

class Oauth extends Auth_Strategy
{
	public $name = 'oauth';
	public $provider;
	
	public function authenticate()
	{
		// Create an consumer from the config
		$consumer = Consumer::make($this->config);
		
		// Load the provider
		$provider = Provider::make($this->provider);
		
		// Create the URL to return the user to
		$callback = array_get($this->config, 'callback') ?: \URL::to(
			\Config::get('oneauth::urls.callback', 'connect/callback'),
			null,
			false,
			false
		);
		
		$callback = rtrim($callback, '/').'/'.$this->provider;
		
		// Add the callback URL to the consumer
		$consumer->callback($callback); 

		// Get a request token for the consumer
		$token = $provider->request_token($consumer);

		// Store the token
		\Cookie::put('oauth_token', base64_encode(serialize($token)));

		// Redirect to the twitter login page
		return \Redirect::to($provider->authorize_url($token, array(
			'oauth_callback' => $callback,
		)));
	}
	
	public function callback()
	{
		// Create an consumer from the config
		$this->consumer = Consumer::make($this->config);

		// Load the provider
		$this->provider = Provider::make($this->provider);
		
		if ($token = \Cookie::get('oauth_token'))
		{
			// Get the token from storage
			$this->token = unserialize(base64_decode($token));
		}

		if ( ! property_exists($this, 'token'))
		{
			throw new Exception('Invalid token');
		}
			
		if ($this->token and $this->token->access_token !== \Input::get('oauth_token'))
		{   
			// Delete the token, it is not valid
			\Cookie::forget('oauth_token');

			// Send the user back to the beginning
			throw new Exception('invalid token after coming back to site');
		}

		// Get the verifier
		$verifier = \Input::get('oauth_verifier');

		// Store the verifier in the token
		$this->token->verifier($verifier);

		// Exchange the request token for an access token
		return $this->provider->access_token($this->token, $this->consumer);
	}
}