<?php namespace OneAuth\OAuth\Request;

/**
 * Ported from Kohana\OAuth package
 *
 * @package    Kohana/OAuth
 * @author     Kohana Team
 * @copyright  (c) 2010 Kohana Team
 * @license    http://kohanaframework.org/license
 * @since      3.0.7
 */

use OneAuth\OAuth\Request as OAuth_Request;

class Authorize extends OAuth_Request 
{
	protected $name = 'request';

	// http://oauth.net/core/1.0/#rfc.section.6.2.1
	protected $required = array(
		'oauth_token' => true,
	);

	public function execute(array $options = null)
	{
		return \Redirect::to($this->as_url());
	}
}