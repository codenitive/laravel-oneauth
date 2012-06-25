<?php namespace OneAuth\Auth;

/**
 * Auth Class taken from NinjAuth Package for FuelPHP
 *
 * @package     NinjAuth
 * @author      Phil Sturgeon <https://github.com/philsturgeon>
 */
use \Auth, \Config, \Event, \Exception, \Session;

class Core extends Auth
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
		
		return \Redirect::to($path);
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
		$client    = Client::where('provider', '=', $user_data['provider'])
					->where('uid', '=', $user_data['info']['uid'])
					->first();

		if (is_null($client))
		{
			$client = new Client(array(
				'uid'      => $user_data['info']['uid'],
				'provider' => $user_data['provider'],
			));
		}

		// Link to user using Auth.
		if (\Auth::check())
		{
			$client->user_id = \Auth::user()->id;
		}

		$client->access_token  = $user_data['token']->access_token ?: null;
		$client->secret        = $user_data['token']->secret ?: null;
		$client->refresh_token = $user_data['token']->refresh_token ?: null;

		$client->save();

		Event::fire('oneauth.logged', array($client, $user_data));
		Session::put('oneauth', $user_data);

		return Core::redirect(\Auth::check() ? 'logged_in' : 'registration');
	}

	/**
	 * Sync oneauth_clients with user
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