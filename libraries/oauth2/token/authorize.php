<?php namespace OneAuth\OAuth2\Token;

/**
 * Ported from FuelPHP \OAuth2 package
 * 
 * @package    FuelPHP/OAuth2
 * @category   Token
 * @author     Phil Sturgeon
 * @copyright  (c) 2012 HappyNinjas Ltd
 */

use OneAuth\OAuth2\Token as OAuth2_Token,
	OneAuth\OAuth2\Exception;

class Authorize extends OAuth2_Token
{
	/**
	 * @var  string  code
	 */
	protected $code;

	/**
	 * @var  string  redirect_uri
	 */
	protected $redirect_uri;

	/**
	 * Sets the token, expiry, etc values.
	 *
	 * @param   array   token options
	 * @return  void
	 */
	public function __construct(array $options)
	{
		if ( ! isset($options['code']))
	    {
            throw new Exception(array('message' => 'Required option not passed: code'));
        }

        elseif ( ! isset($options['redirect_uri']))
        {
            throw new Exception(array('message' => 'Required option not passed: redirect_uri'));
        }
		
		$this->code         = $options['code'];
		$this->redirect_uri = $options['redirect_uri'];
	}

	/**
	 * Returns the token key.
	 *
	 * @return  string
	 */
	public function __toString()
	{
		return (string) $this->code;
	}

} // End Token_Access
