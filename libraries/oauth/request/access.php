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

use OneAuth\OAuth\Request as OAuth_Request,
	OneAuth\OAuth\Response as OAuth_Response;

class Access extends OAuth_Request 
{
	protected $name = 'access';

	protected $required = array(
		'oauth_consumer_key'     => true,
		'oauth_token'            => true,
		'oauth_signature_method' => true,
		'oauth_signature'        => true,
		'oauth_timestamp'        => true,
		'oauth_nonce'            => true,
		'oauth_version'          => true,
	);

	public function execute(array $options = null)
	{
		return OAuth_Response::make(parent::execute($options));
	}
}