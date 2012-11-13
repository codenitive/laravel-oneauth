<?php

Bundle::start('oneauth');

class OAuthRequestTest extends PHPUnit_Framework_TestCase {
	
	private $request = null;

	/**
	 * Setup the test environment.
	 */
	public function setUp()
	{
		$this->request = new RequestStub('POST', 'http://google.com', array('foo' => 'foobar'));
	}

	/**
	 * Teardown the test environment.
	 */
	public function tearDown()
	{
		unset($this->request);
	}

	/**
	 * Test make returning the right instance.
	 *
	 * @test
	 */
	public function testMakeReturningProperInstance()
	{
		$this->assertInstanceOf('OneAuth\OAuth\Request\Access', OneAuth\OAuth\Request::make('access', 'GET'));
		$this->assertInstanceOf('OneAuth\OAuth\Request\Authorize', OneAuth\OAuth\Request::make('authorize', 'GET'));
		$this->assertInstanceOf('OneAuth\OAuth\Request\Credentials', OneAuth\OAuth\Request::make('credentials', 'GET'));
		$this->assertInstanceOf('OneAuth\OAuth\Request\Resource', OneAuth\OAuth\Request::make('resource', 'GET'));
		$this->assertInstanceOf('OneAuth\OAuth\Request\Token', OneAuth\OAuth\Request::make('token', 'GET'));
	}

	/**
	 * Test properties is setup properly upon construct
	 *
	 * @test
	 */
	public function testPropertiesIsProperlySetup()
	{
		$this->assertEquals('stub', $this->request->name);
		$this->assertEquals('POST', $this->request->method);
		$this->assertEquals('foobar', $this->request->params['foo']);
	}

	/**
	 * Test timestamp() returning proper time()
	 *
	 * @test
	 */
	public function testTimestampIsProperlyGenerated()
	{
		$this->assertTrue(is_int($this->request->timestamp()));
	}

	/**
	 * Test nonce returning 40 char random alphanumeric string
	 *
	 * @test
	 */
	public function testNonceReturningProperString()
	{
		$nonce = $this->request->nonce();

		$this->assertEquals(40, strlen($nonce));
		$this->assertRegExp('/([a-zA-Z0-9]{40})/', $nonce);
	}
}

class RequestStub extends OneAuth\OAuth\Request {

	protected $name = 'stub';
	
}
