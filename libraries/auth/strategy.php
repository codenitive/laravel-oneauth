<?php namespace OneAuth\Auth;

/**
 * Auth Class taken from NinjAuth Package for FuelPHP
 *
 * @package     NinjAuth
 * @author      Phil Sturgeon <https://github.com/philsturgeon>
 */
use \Config, \Event, \Session;

abstract class Strategy 
{
	public $provider = null;
	public $config   = array();

	/**
	 * Strategy name
	 *
	 * @access  public
	 * @var     string
	 */
	public $name     = null;
	
	/**
	 * List of available provider
	 * 
	 * @static
	 * @access  protected
	 * @var     array
	 */
	protected static $providers = array(
		//'dropbox'    => 'OAuth',
		//'facebook'   => 'OAuth2',
		//'flickr'     => 'OAuth',
		//'foursquare' => 'OAuth2',
		//'github'     => 'OAuth2',
		//'google'     => 'OAuth2',
		//'instagram'  => 'OAuth2',
		//'linkedin'   => 'OAuth',
		//'openid'     => 'OpenId',
		//'tumblr'     => 'OAuth',
		'twitter'    => 'OAuth',
		//'unmagnify'  => 'OAuth2',
		//'youtube'    => 'OAuth2',
		//'vimeo'      => 'OAuth',
		//'windowlive' => 'OAuth2',
	);

	/**
	 * Generic construct method
	 *
	 * @access  public
	 * @return  void
	 */
	public function __construct($provider)
	{
		$this->provider = $provider;
		
		$this->config   = Config::get("oneauth::api.providers.{$provider}", null);

		if (is_null($this->config))
		{
			throw new Strategy\Exception(sprintf('Provider "%s" has no config.', $provider));
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
	public static function make($provider = null)
	{
		$strategy = Config::get("oneauth::providers.{$provider}.strategy") ?: array_get(static::$providers, $provider);

		if (is_null($strategy))
		{
			throw new Strategy\Exception(sprintf('Provider "%s" has no strategy.', $provider));
		}

		switch ($strategy)
		{
			case 'OAuth' :
				return new Strategy\Oauth($provider);
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
		
		$logged_in = \Auth::check();
		$user_info = static::get_user_info($strategy, $token);

		$user_data = array(
			'token'    => $token,
			'info'     => $user_info,
			'provider' => $strategy->provider->name,
		);

		$client = Client::where('provider', '=', $user_data['provider'])
					->where('uid', '=', $user_data['info']['uid'])
					->first();

		if (is_null($client))
		{
			$client = new Client(array(
				'uid'      => $user_data['info']['uid'],
				'provider' => $strategy->provider->name,
			));
		}

		// Link to user using Auth.
		if ($logged_in)
		{
			$client->user_id = \Auth::user()->id;
		}

		$client->access_token  = $user_data['token']->access_token ?: null;
		$client->secret        = $user_data['token']->secret ?: null;
		$client->refresh_token = $user_data['token']->refresh_token ?: null;

		$client->save();

		Event::fire('oneauth.logged', $user_data);
		Session::put('oneauth', $user_data);

		return Core::redirect($logged_in ? 'logged_in' : 'registration');
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
		switch ($strategy->name)
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