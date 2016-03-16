<?php
namespace Redaxscript\Tests;

use Redaxscript\Auth;
use Redaxscript\Request;

/**
 * AuthTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class AuthTest extends TestCase
{
	/**
	 * instance of the request class
	 *
	 * @var object
	 */

	protected $_request;

	/**
	 * setUp
	 *
	 * @since 3.0.0
	 */

	protected function setUp()
	{
		$this->_request = Request::getInstance();
	}

	/**
	 * testInit
	 *
	 * @since 3.0.0
	 */

	public function testInit()
	{
		/* setup */

		$auth = new Auth($this->_request);
		$auth->init();

		/* actual */

		$actual = $auth;

		/* compare */

		$this->assertInstanceOf('Redaxscript\Auth', $actual);
	}

	/**
	 * testLogin
	 *
	 * @since 3.0.0
	 */

	public function testLogin()
	{
		/* setup */

		$auth = new Auth($this->_request);
		$auth->login(1);

		/* actual */

		$actual = $auth;

		/* compare */

		$this->assertInstanceOf('Redaxscript\Auth', $actual);
	}

	/**
	 * testGetPermissionNewFor
	 *
	 * @since 3.0.0
	 */

	public function testGetPermissionNewFor()
	{
		/* setup */

		$auth = new Auth($this->_request);
		$auth->login(1);

		/* actual */

		$actual = $auth->getPermissionNewFor('articles');

		/* compare */

		$this->assertTrue($actual);
	}
}