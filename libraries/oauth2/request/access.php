<?php namespace OneAuth\OAuth2\Request;

/**
 * Ported from Kohana\OAuth package
 *
 * @package    Kohana/OAuth
 * @author     Kohana Team
 * @copyright  (c) 2010 Kohana Team
 * @license    http://kohanaframework.org/license
 * @since      3.0.7
 */

use OneAuth\OAuth2\Request as OAuth2_Request,
	OneAuth\OAuth2\Response as OAuth2_Response;

class Access extends OAuth2_Request 
{
	protected $name = 'access';

	public function execute(array $options = null)
	{
		return OAuth2_Response::make(parent::execute($options));
	}
}