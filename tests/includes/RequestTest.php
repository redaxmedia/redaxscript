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
	 * instance of the request class
	 *
	 * @var object
	 */

	protected $_request;

	/**
	 * setUp
	 *
	 * @since 2.4.0
	 */

	protected function setUp()
	{
		$this->_request = Request::getInstance();
		$this->_request->init();
	}

	/**
	 * testAll
	 *
	 * @since 2.2.0
	 */

	public function testAll()
	{
		/* result */

		$result = $this->_request->get();

		/* compare */

		$this->assertArrayHasKey('_SERVER', $result);
	}

	/**
	 * testGlobals
	 *
	 * @since 2.2.0
	 */

	public function testGlobals()
	{
		/* setup */

		$this->_request->set('testKey', 'testValue');

		/* result */

		$result = $this->_request->get('testKey');

		/* compare */

		$this->assertEquals('testValue', $result);
	}

	/**
	 * testServer
	 *
	 * @since 2.2.0
	 */

	public function testServer()
	{
		/* setup */

		$this->_request->setServer('testKey', 'testValue');

		/* result */

		$result = $this->_request->getServer('testKey');

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

		$this->_request->setQuery('testKey', 'testValue');

		/* result */

		$result = $this->_request->getQuery('testKey');

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

		$this->_request->setPost('testKey', 'testValue');

		/* result */

		$result = $this->_request->getPost('testKey');

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

		$this->_request->setSession('testKey', 'testValue');

		/* result */

		$result = $this->_request->getSession('testKey');

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

		$this->_request->setCookie('testKey', 'testValue');

		/* result */

		$result = $this->_request->getCookie('testKey');

		/* compare */

		$this->assertEquals('testValue', $result);
	}
}
