<?php namespace OneAuth\OAuth2;

/**
 * OAuth2.0 draft v10 exception handling.
 *
 * @author Originally written by Naitik Shah <naitik@facebook.com>.
 * @author Update to draft v10 by Edison Wong <hswong3i@pantarei-design.com>.
 *
 * Ported from FuelPHP \OAuth2 package
 *
 * @package    FuelPHP/OAuth2
 * @category   Provider
 * @author     Phil Sturgeon
 * @copyright  (c) 2012 HappyNinjas Ltd
 * @license    http://philsturgeon.co.uk/code/dbad-license
 */

class Exception extends \Exception
{
	/**
	 * The result from the API server that represents the exception
	 * information.
	 */
	protected $result;

	/**
	 * Make a new API Exception with the given result.
	 *
	 * @param $result
	 *   The result from the API server.
	 */
	public function __construct($result)
	{
		$this->result = $result;

		$code         = isset($result['code']) ? $result['code'] : 0;

		// OAuth 2.0 Draft 10 style
		if (isset($result['error'])) $message = $result['error'];
		// cURL style
		elseif (isset($result['message'])) $message = $result['message'];
		else $message = 'Unknown Error.';

		parent::__construct($message, (int) $code);
	}

	/**
	 * To make debugging easier.
	 *
	 * @returns
	 *   The string representation of the error.
	 */
	public function __toString()
	{
		$str = $this->getType() . ': ';

		if ($this->code != 0) $str .= $this->code . ': ';

		return $str . $this->message;
	}
}
