<?php

class OAuthResponseTest extends PHPUnit_Framework_TestCase {
	
	/**
	 * Setup the test environment.
	 */
	public function setUp()
	{
		Bundle::start('oneauth');
	}

	/**
	 * Test OneAuth\OAuth\Response::__construct
	 *
	 * @test
	 */
	public function testInstanceOfResponse()
	{
		$response1 = new OneAuth\OAuth\Response;
		$response2 = OneAuth\OAuth\Response::make();

		$this->assertInstanceOf('OneAuth\OAuth\Response', $response1);
		$this->assertInstanceOf('OneAuth\OAuth\Response', $response2);
	}
}
