<?php

class AuthControllerTest extends PHPUnit_Framework_TestCase 
{
	/**
	 * Setup the test environment.
	 */
	public function setUp()
	{
		Bundle::start('oneauth');
	}

	/**
	 * Test OneAuth\Auth\Controller::action_session() when provider is empty
	 *
	 * @test
	 */
	public function testSessionRequestWithoutProvider()
	{
		$response = Controller::call('oneauth::connect@session');

		$this->assertInstanceOf('Laravel\Response', $response);
		$this->assertEquals(404, $response->foundation->getStatusCode());
	}

	/**
	 * Test OneAuth\Auth\Controller::action_session() with valid provider
	 *
	 * @test
	 */
	public function testSessionRequestWithProvider()
	{
		$response = Controller::call('oneauth::connect@session', array('twtter'));

		$this->assertInstanceOf('Laravel\Response', $response);
		$this->assertEquals(200, $response->foundation->getStatusCode());
	}

	/**
	 * Test OneAuth\Auth\Controller::action_callback() when provider is empty
	 *
	 * @test
	 */
	public function testCallbackRequestWithoutProvider()
	{
		$response = Controller::call('oneauth::connect@callback');

		$this->assertInstanceOf('Laravel\Response', $response);
		$this->assertEquals(404, $response->foundation->getStatusCode());
	}

	/**
	 * Test OneAuth\Auth\Controller::action_callback() with valid provider
	 *
	 * @test
	 */
	public function testCallbackRequestWithProvider()
	{
		$response = Controller::call('oneauth::connect@callback', array('twtter'));

		$this->assertInstanceOf('Laravel\Response', $response);
		$this->assertEquals(200, $response->foundation->getStatusCode());
	}
}
