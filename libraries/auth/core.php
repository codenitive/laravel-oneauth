<?php namespace OneAuth\Auth;

/**
 * Auth Class taken from NinjAuth Package for FuelPHP
 *
 * @package     NinjAuth
 * @author      Phil Sturgeon <https://github.com/philsturgeon>
 */
use \Config, \Event, \Exception, \IoC, \Redirect, \Session;

class Core
{
	/**
	 * Redirect user based on type
	 *
	 * @static
	 * @access  public
	 * @param   string  $type
	 * @return  void
	 * @throws  AuthException
	 */
	public static function redirect($type)
	{
		if (is_null($path = Config::get("oneauth::urls.{$type}")))
		{
			throw new Exception(__METHOD__.": Unable to redirect using {$type} type.");
		}
		
		return Redirect::to(value($path));
	}

	/**
	 * Login ar register new oneauth_clients
	 *
	 * @static
	 * @access  public
	 * @param   array       $user_data
	 * @return  Redirect
	 */
	public static function login($user_data)
	{
		$client = Client::where('provider', '=', $user_data['provider'])
					->where('uid', '=', $user_data['info']['uid'])
					->first();

		if (is_null($client))
		{
			$client = new Client(array(
				'uid'      => $user_data['info']['uid'],
				'provider' => $user_data['provider'],
				'user_id'  => 0,
			));
		}

		// Link to user using Auth.
		if ( ! is_null($user = IoC::resolve('oneauth.driver: auth.user')))
		{
			$client->user_id = $user->id;
		}

		$client->access_token  = $user_data['token']->access_token ?: null;
		$client->secret        = $user_data['token']->secret ?: null;
		$client->refresh_token = $user_data['token']->refresh_token ?: null;

		$client->save();

		Event::fire('oneauth.logged', array($client, $user_data));

		$user_data['token'] = serialize($user_data['token']);

		Session::put('oneauth', $user_data);

		return Core::redirect(IoC::resolve('oneauth.driver: auth.check') ? 'logged_in' : 'registration');
	}

	/**
	 * Retrieve user information and access token from Session, this is to
	 * allow developer to reuse the access token to retrieve or send API
	 * request to server without having to reinitiate OAuth\Token\Access
	 * class.
	 *
	 * @static
	 * @access  public
	 * @return  array
	 */
	public static function session()
	{
		if ( ! is_null($user_data = Session::get('oneauth')))
		{
			$user_data['token'] = unserialize($user_data['token']);
		}

		return $user_data;
	}

	/**
	 * Sync `oneauth_clients` with `users` table
	 *
	 * @static
	 * @access  public
	 * @param   array       $user_data
	 * @return  bool
	 */
	public static function sync($user_id)
	{
		if ( ! Session::has('oneauth')) return;

		$user_data = Session::get('oneauth');

		$client = Client::where('provider', '=', $user_data['provider'])
					->where('uid', '=', $user_data['info']['uid'])
					->first();

		if (is_null($client)) return;

		$client->user_id = $user_id;
		$client->save();

		return true;
	}
}
