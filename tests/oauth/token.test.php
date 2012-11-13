<?php

Bundle::start('oneauth');

class OAuthTokenTest extends PHPUnit_Framework_TestCase {
	
	private $token = null;

	/**
	 * Setup the test environment.
	 */
	public function setUp()
	{
		$this->token = new TokenStub(array(
			'access_token' => 'foo',
			'secret'       => 'foobar',
			'uid'          => 'oneauth1',
		));
	}

	/**
	 * Teardown the test environment.
	 */
	public function tearDown()
	{
		unset($this->token);
	}

	/**
	 * Test OneAuth\OAuth\Token::make() returning valid instance
	 *
	 * @test
	 */
	public function testMakeReturnValidInstanceOf()
	{
		$options = array(
			'access_token' => 'foo',
			'secret'       => 'foobar',
		);
		
		$token1 = OneAuth\OAuth\Token::make('access', $options);
		$token2 = OneAuth\OAuth\Token::make('request', $options);

		$this->assertInstanceOf('OneAuth\OAuth\Token\Access', $token1);
		$this->assertInstanceOf('OneAuth\OAuth\Token\Request', $token2);
	}

	/**
	 * Test OneAuth\OAuth\Token::make() without options throw an exception
	 *
	 * @test
	 * @expectedException OneAuth\OAuth\Exception
	 */
	public function testMakeWithoutOptionThrowAnException()
	{
		$token = OneAuth\OAuth\Token::make('access', array());
	}

	/**
	 * Test OneAuth\OAuth\Token returning proper properties
	 *
	 * @test
	 */
	public function testConstructReturningProperProperties()
	{
		$this->assertEquals('stub', $this->token->name);
		$this->assertEquals('foo', $this->token->access_token);
		$this->assertEquals('foobar', $this->token->secret);
		$this->assertEquals('oneauth1', $this->token->uid);
	}
}

class TokenStub extends OneAuth\OAuth\Token {

	protected $name = 'stub';

}
