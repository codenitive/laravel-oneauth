<?php

class OAuthCoreTest extends PHPUnit_Framework_TestCase {
	
	/**
	 * Setup the test environment.
	 */
	public function setUp()
	{
		Bundle::start('oneauth');
	}

	/**
	 * Test returning valid OAuth version
	 *
	 * @test
	 */
	public function testOAuthVersion()
	{
		$this->assertEquals('1.0', OneAuth\OAuth\Core::$version);
	}
}
