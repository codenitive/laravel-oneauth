<?php

class TestCore extends PHPUnit_Framework_TestCase
{
	/**
	 * Setup the test
	 */
	public function setUp()
	{
		Bundle::start('oneauth');

		Config::set('application.url', 'http://localhost');
		Config::set('oneauth::urls', array(
			'registration' => 'auth/register',
			'login'        => 'auth/login',
			'callback'     => 'auth/callback',
			
			'registered'   => 'auth/home',
			'logged_in'    => 'auth/account',
		));
	}

	/**
	 * Test OneAuth\Auth\Core::redirect()
	 *
	 * @test
	 */
	public function testRedirect()
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