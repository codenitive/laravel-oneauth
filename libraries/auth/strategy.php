<?php namespace OneAuth\Auth;

/**
 * Auth Class taken from NinjAuth Package for FuelPHP
 *
 * @package     NinjAuth
 * @author      Phil Sturgeon <https://github.com/philsturgeon>
 */
use \Config, \Event;

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
		
		$user_info = static::get_user_info($strategy, $token);

		$user_data = array(
			'token'    => $token,
			'info'     => $user_info,
			'provider' => $strategy->provider->name,
		);
		
		if (Auth::check())
		{
			// User already logged in 
			$user_id    = Auth::user()->id;
			$oneauth    = Model::where('user_id', '=', $user_id);
			$num_linked = $oneauth->count();
		
			// Allowed multiple providers, or not authed yet?
			if (0 === $num_linked or true === Config::get('oneauth::api.link_multiple_providers'))
			{
				try 
				{
					$user_auth->link_account($user_data);
					
					Event::fire('oneauth.link_authentication', $user_data);
				}
				catch (\Exception $e)
				{
					throw new Strategy\Exception("Unable to retrieve valid user information from requested access token");
				}
				
				// Attachment went ok so we'll redirect
				Core::redirect('logged_in');
			}
			else
			{
				$providers = array_keys($accounts);

				throw new Strategy\Exception(sprintf('This user is already linked to "%s".', $providers[0]));
			}
		}
		// The user exists, so send him on his merry way as a user
		else 
		{
			try 
			{
				$user_auth->login_token($user_data);

				Event::fire('oneauth.link_authentication', $user_data);

				// credentials ok, go right in
				Core::redirect('logged_in');
			}
			catch (\Exception $e)
			{
				Session::set('oneauth', $user_data);

				Core::redirect('registration');
			}
		}
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