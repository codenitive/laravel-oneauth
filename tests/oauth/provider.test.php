<?php

Bundle::start('oneauth');

class OAuthProviderTest extends PHPUnit_Framework_TestCase {
	
	private $provider = null;

	/**
	 * Setup the test environment.
	 */
	public function setUp()
	{
		$this->provider = new ProviderStub;
	}

	/**
	 * Test OneAuth\OAuth\Provider returning proper URL
	 *
	 * @test
	 */
	public function testUrls()
	{
		$this->assertEquals('http://foobar.com/oauth/request_token', $this->provider->url_request_token());
		$this->assertEquals('http://foobar.com/oauth/authorize', $this->provider->url_authorize());
		$this->assertEquals('http://foobar.com/oauth/access_token', $this->provider->url_access_token());
	}
}

use OneAuth\OAuth\Consumer,
	OneAuth\OAuth\Token;

class ProviderStub extends OneAuth\OAuth\Provider {

	public $name = 'stub';

	public function url_request_token()
	{
		return 'http://foobar.com/oauth/request_token';
	}

	public function url_authorize()
	{
		return 'http://foobar.com/oauth/authorize';
	}

	public function url_access_token()
	{
		return 'http://foobar.com/oauth/access_token';
	}
	
	public function get_user_info(Token $token, Consumer $consumer)
	{
		return array();
	}
}
