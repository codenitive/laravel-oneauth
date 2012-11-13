<?php

class AuthStrategyTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Setup the test environment.
	 */
	public function setUp()
	{
		Bundle::start('oneauth');
	}

	/**
	 * Test OneAuth\Auth\Strategy::__construct() on OAuth Strategy
	 *
	 * @test
	 */
	public function testInstanceOfOAuthStrategy()
	{
		$oauth = new OneAuth\Auth\Strategy\OAuth('twitter');
		$this->assertInstanceOf('OneAuth\Auth\Strategy', $oauth);
		$this->assertInstanceOf('OneAuth\Auth\Strategy\OAuth', $oauth);
		$this->assertEquals('oauth', $oauth->name);
		$this->assertEquals('twitter', $oauth->provider);
	}

	/**
	 * Test OneAuth\Auth\Strategy::__construct() on OAuth2 Strategy
	 * 
	 * @test
	 */
	public function testInstanceOfOAuth2Strategy()
	{
		$oauth2 = new OneAuth\Auth\Strategy\OAuth2('facebook');
		
		$this->assertInstanceOf('OneAuth\Auth\Strategy', $oauth2);
		$this->assertInstanceOf('OneAuth\Auth\Strategy\OAuth2', $oauth2);
		$this->assertEquals('oauth2', $oauth2->name);
		$this->assertEquals('facebook', $oauth2->provider);
	}

	/**
	 * Test OneAuth\Auth\Strategy::make() without a proper provider throw an exception
	 *
	 * @test
	 * @expectedException OneAuth\Auth\Strategy\Exception
	 */
	public function testMakeInvalidProviderThrowException()
	{
		OneAuth\Auth\Strategy::make('foo');
	}
}
