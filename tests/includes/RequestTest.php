<?php
namespace Redaxscript\Tests;
use Redaxscript\Request;

/**
 * RequestTest
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class RequestTest extends TestCase
{
	/**
	 * setUp
	 *
	 * @since 2.2.0
	 */

	protected function setUp()
	{
		Request::init();
	}

	/**
	 * testGetAll
	 *
	 * @since 2.2.0
	 */

	public function testGetAll()
	{
		/* result */

		$result = Request::get();

		/* compare */

		$this->assertArrayHasKey('_SERVER', $result);
	}

	/**
	 * testServer
	 *
	 * @since 2.2.0
	 */

	public function testServer()
	{
		/* setup */

		Request::setServer('testKey', 'testValue');

		/* result */

		$result = Request::getServer('testKey');

		/* compare */

		$this->assertEquals('testValue', $result);
	}

	/**
	 * testQuery
	 *
	 * @since 2.2.0
	 */

	public function testQuery()
	{
		/* setup */

		Request::setQuery('testKey', 'testValue');

		/* result */

		$result = Request::getQuery('testKey');

		/* compare */

		$this->assertEquals('testValue', $result);
	}

	/**
	 * testPost
	 *
	 * @since 2.2.0
	 */

	public function testPost()
	{
		/* setup */

		Request::setPost('testKey', 'testValue');

		/* result */

		$result = Request::getPost('testKey');

		/* compare */

		$this->assertEquals('testValue', $result);
	}

	/**
	 * testSession
	 *
	 * @since 2.2.0
	 */

	public function testSession()
	{
		/* setup */

		Request::setSession('testKey', 'testValue');

		/* result */

		$result = Request::getSession('testKey');

		/* compare */

		$this->assertEquals('testValue', $result);
	}

	/**
	 * testCookie
	 *
	 * @since 2.2.0
	 */

	public function testCookie()
	{
		/* setup */

		Request::setCookie('testKey', 'testValue');

		/* result */

		$result = Request::getCookie('testKey');

		/* compare */

		$this->assertEquals('testValue', $result);
	}
}
