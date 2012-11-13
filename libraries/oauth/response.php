<?php namespace OneAuth\OAuth;

/**
 * Ported from Kohana\OAuth package
 *
 * @package    Kohana/OAuth
 * @author     Kohana Team
 * @copyright  (c) 2010 Kohana Team
 * @license    http://kohanaframework.org/license
 * @since      3.0.7
 */

class Response
{
	/**
	 * Create an instance of Response
	 *
	 * @static
	 * @access public
	 * @param  mixed    $body
	 * @return Response
	 */
	public static function make($body = NULL)
	{
		return new static($body);
	}

	/**
	 * @var   array   response parameters
	 */
	protected $params = array();

	/**
	 * Construct a new instance
	 * 
	 * @access public
	 * @param  mixed    $body
	 * @return void
	 */
	public function __construct($body = NULL)
	{
		if ($body)
		{
			$this->params = Core::parse_params($body);
		}
	}

	/**
	 * Return the value of any protected class variable.
	 *
	 *     // Get the response parameters
	 *     $params = $response->params;
	 *
	 * @param   string  variable name
	 * @return  mixed
	 */
	public function __get($key)
	{
		return $this->$key;
	}

	public function param($name, $default = NULL)
	{
		return array_get($this->params, $name, $default);
	}
}