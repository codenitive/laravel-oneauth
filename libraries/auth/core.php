<?php namespace OneAuth\Auth;

/**
 * Auth Class taken from NinjAuth Package for FuelPHP
 *
 * @package     NinjAuth
 * @author      Phil Sturgeon <https://github.com/philsturgeon>
 */
use \Auth, \Config, \Exception;

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
}