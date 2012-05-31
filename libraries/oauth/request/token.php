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

class Token extends OAuth_Request 
{
	protected $name = 'request';

	// http://oauth.net/core/1.0/#rfc.section.6.3.1
	protected $required = array(
		'oauth_callback'         => true,
		'oauth_consumer_key'     => true,
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