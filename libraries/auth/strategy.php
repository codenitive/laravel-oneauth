<?php namespace OneAuth\Auth;

/**
 * Auth Class taken from NinjAuth Package for FuelPHP
 *
 * @package     NinjAuth
 * @author      Phil Sturgeon <https://github.com/philsturgeon>
 */
use \Config;

abstract class Strategy
{
	/**
	 * Provider name
	 *
	 * @access  public
	 * @var     string
	 */
	public $name = null;

	/**
	 * Strategy type (OAuth or OAuth2)
	 *
	 * @access  public
	 * @var     string
	 */
	public $type = null;

	public $config = array();

	/**
	 * List of available provider
	 *
	 * @static
	 * @access  protected
	 * @var     array
	 */
	protected static $provider_list = array(
		'basecamp'   => 'OAuth2',
		'dropbox'    => 'OAuth',
		'facebook'   => 'OAuth2',
		'flickr'     => 'OAuth',
		'foursquare' => 'OAuth2',
		'github'     => 'OAuth2',
		'google'     => 'OAuth2',
		'instagram'  => 'OAuth2',
		'linkedin'   => 'OAuth',
		//'openid'     => 'OpenId',
		'paypal'     => 'OAuth2',
		'soundcloud' => 'OAuth2',
		'tumblr'     => 'OAuth',
		'twitter'    => 'OAuth',
		'vimeo'      => 'OAuth',
		'windowlive' => 'OAuth2',
	);

	public static function add($type, $name)
	{
		if (in_array($type, array('OAuth', 'OAuth2')) and ! empty($name))
		{
			static::$provider_list[$name] = $type;
		}
	}

	/**
	 * Generic construct method
	 *
	 * @access  public
	 * @return  void
	 */
	public function __construct($name)
	{
		$this->name = $name;

		$this->config   = Config::get("oneauth::api.providers.{$name}", null);

		if (is_null($this->config))
		{
			throw new Strategy\Exception(sprintf('Provider "%s" has no config.', $name));
		}
	}

	/**
	 * Forge a new strategy
	 *
	 * @static
	 * @access  public
	 * @return  Auth_Strategy
	 * @throws  Strategy\Exception
	 */
	public static function make($name = null)
	{
		$strategy = Config::get("oneauth::providers.{$name}.strategy") ?: array_get(static::$provider_list, $name);

		if (is_null($strategy))
		{
			throw new Strategy\Exception(sprintf('Provider "%s" has no strategy.', $name));
		}

		switch ($strategy)
		{
			case 'OAuth' :
				return new Strategy\Oauth($name);
				break;
			case 'OAuth2' :
				return new Strategy\Oauth2($name);
				break;
		}
	}

	/**
	 * Determine whether authenticated user should be continue to login or register new user
	 *
	 * @static
	 * @access  public
	 * @param   object   $strategy
	 * @return  void
	 * @throws  Strategy\Exception
	 */
	public static function login_or_register($strategy)
	{
		$token     = $strategy->callback();
		$user_info = static::get_user_info($strategy, $token);
dd($user_info);
		$user_data = array(
			'token'    => $token,
			'info'     => $user_info,
			'provider' => $strategy->provider->name,
		);

		return Core::login($user_data);
	}

	/**
	 * Get user information from provider
	 *
	 * @static
	 * @access  protected
	 * @param   object      $strategy
	 * @param   object      $response
	 * @return  array
	 * @throws  Strategy\Exception
	 */
	protected static function get_user_info($strategy, $token)
	{
		switch ($strategy->type)
		{
			case 'oauth':
				return $strategy->provider->get_user_info($token, $strategy->consumer);
			break;

			case 'oauth2':
				return $strategy->provider->get_user_info($token);
			break;

			case 'openid':
				return $strategy->get_user_info($token);
			break;

			default:
				throw new Strategy\Exception("Unsupported Strategy: {$strategy->name}");
		}
	}

	abstract public function authenticate();
}
