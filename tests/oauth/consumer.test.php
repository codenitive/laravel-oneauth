<?php

class OAuthConsumerTest extends PHPUnit_Framework_TestCase {
	
	/**
	 * Setup the test environment.
	 */
	public function setUp()
	{
		Bundle::start('oneauth');
	}
	
	/**
	 * Test OneAuth\OAuth\Consumer::__construct()
	 *
	 * @test
	 */
	public function testConstructConsumer()
	{
		$options = array(
			'key'    => 'foo',
			'secret' => 'foobar',
		);

		$consumer1 = new OneAuth\OAuth\Consumer($options);
		$consumer2 = OneAuth\OAuth\Consumer::make($options);

		$this->assertInstanceOf('OneAuth\OAuth\Consumer', $consumer1);
		$this->assertInstanceOf('OneAuth\OAuth\Consumer', $consumer2);
	
		$this->assertEquals('foo', $consumer1->key);
		$this->assertEquals('foobar', $consumer1->secret);
	}

	/**
	 * Test OneAuth\OAuth\Consumer construct throws an Exception
	 *
	 * @test
	 * @expectedException OneAuth\OAuth\Exception
	 */
	public function testConstructConsumerThrowAnException()
	{
		$consumer = new OneAuth\OAuth\Consumer;
	}
	
	/**
	 * Test able to change callback
	 *
	 * @test
	 */
	public function testChangeCallbackValue()
	{
		$options = array(
			'key'    => 'foo',
			'secret' => 'foobar',
		);

		$consumer = new OneAuth\OAuth\Consumer($options);
		$consumer->callback('foo callback');

		$this->assertEquals('foo callback', $consumer->callback);
	}
}
