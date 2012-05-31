<?php namespace OneAuth\Auth\Strategy;

/**
 * Auth Class taken from NinjAuth Package for FuelPHP
 *
 * @package     NinjAuth
 * @author      Phil Sturgeon <https://github.com/philsturgeon>
 */

use OneAuth\Auth\Strategy as Auth_Strategy,
	OneAuth\OAuth2\Provider;

class Oauth2 extends Auth_Strategy
{
	public $name = 'oauth2';
	public $provider;
	
	public function authenticate()
	{
		// Load the provider
		$provider = Provider::make($this->provider, $this->config);
		
		// Grab a callback from the config
		if ($provider->callback === null)
		{
			$callback           = \URL::to(\Config::get('oneauth::urls.callback', 'connect/callback'));
			$callback           = rtrim($callback, '/').'/'.$this->provider;
			$provider->callback = $callback;
		}
		
		return $provider->authorize(array(
			'redirect_uri' => $provider->callback
		));
	}
	
	public function callback()
	{
		// Load the provider
		$this->provider = Provider::make($this->provider, $this->config);
		
		return $this->provider->access(\Input::get('code'));
	}
}