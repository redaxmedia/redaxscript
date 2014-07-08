<?php

/**
 * Redaxscript Request Test
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class Redaxscript_Request_Test extends PHPUnit_Framework_TestCase
{
	/**
	 * setUp
	 *
	 * @since 2.2.0
	 */

	protected function setUp()
	{
		Redaxscript_Request::init();
	}

	/**
	 * testGetAll
	 *
	 * @since 2.2.0
	 */

	public function testGetAll()
	{
		/* result */

		$result = Redaxscript_Request::get();

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

		Redaxscript_Request::setServer('testKey', 'testValue');

		/* result */

		$result = Redaxscript_Request::getServer('testKey');

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

		Redaxscript_Request::setQuery('testKey', 'testValue');

		/* result */

		$result = Redaxscript_Request::getQuery('testKey');

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

		Redaxscript_Request::setPost('testKey', 'testValue');

		/* result */

		$result = Redaxscript_Request::getPost('testKey');

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

		Redaxscript_Request::setSession('testKey', 'testValue');

		/* result */

		$result = Redaxscript_Request::getSession('testKey');

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

		Redaxscript_Request::setCookie('testKey', 'testValue');

		/* result */

		$result = Redaxscript_Request::getCookie('testKey');

		/* compare */

		$this->assertEquals('testValue', $result);
	}
}
