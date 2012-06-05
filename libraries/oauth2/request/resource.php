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

use OneAuth\OAuth2\Request as OAuth2_Request;

class Resource extends OAuth2_Request 
{
	protected $name = 'resource';
}