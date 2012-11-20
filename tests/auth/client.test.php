<?php

class AuthClientTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Setup the test environment.
	 */
	public function setUp()
	{
		Bundle::start('oneauth');
	}

	/**
	 * Test OneAuth\Auth\Client is an instanceof Eloquent
	 *
	 * @test
	 */
	public function testInstanceOfEloquent()
	{
		$client = new OneAuth\Auth\Client;

		$this->assertInstanceOf('\Eloquent', $client);
	}

	/**
	 * Test OneAuth\Auth\Client::$table
	 *
	 * @test
	 */
	public function testTableNameIsValid()
	{
		$this->assertEquals('oneauth_clients', OneAuth\Auth\Client::$table);
	}
}
