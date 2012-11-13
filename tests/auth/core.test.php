<?php

class AuthCoreTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Setup the test environment.
	 */
	public function setUp()
	{
		Bundle::start('oneauth');

		Config::set('application.url', 'http://localhost');
		Config::set('application.index', '');
		Config::set('oneauth::urls', array(
			'registration' => 'auth/register',
			'login'        => 'auth/login',
			'callback'     => 'auth/callback',
			
			'registered'   => 'auth/home',
			'logged_in'    => 'auth/account',
		));
	}

	/**
	 * Teardown the test environment.
	 */
	public function tearDown()
	{
		Config::set('application.url', '');
		Config::set('application.index', 'index.php');
	}

	/**
	 * Test OneAuth\Auth\Core::redirect()
	 *
	 * @test
	 */
	public function testRedirection()
	{
		$output = OneAuth\Auth\Core::redirect('registration');

		$this->assertInstanceOf('Laravel\Redirect', $output);
		$this->assertEquals('http://localhost/auth/register', $output->foundation->headers->get('Location'));

		$output = OneAuth\Auth\Core::redirect('login');

		$this->assertInstanceOf('Laravel\Redirect', $output);
		$this->assertEquals('http://localhost/auth/login', $output->foundation->headers->get('Location'));

		$output = OneAuth\Auth\Core::redirect('callback');

		$this->assertInstanceOf('Laravel\Redirect', $output);
		$this->assertEquals('http://localhost/auth/callback', $output->foundation->headers->get('Location'));

		$output = OneAuth\Auth\Core::redirect('registered');

		$this->assertInstanceOf('Laravel\Redirect', $output);
		$this->assertEquals('http://localhost/auth/home', $output->foundation->headers->get('Location'));

		$output = OneAuth\Auth\Core::redirect('logged_in');

		$this->assertInstanceOf('Laravel\Redirect', $output);
		$this->assertEquals('http://localhost/auth/account', $output->foundation->headers->get('Location'));
	}
}
