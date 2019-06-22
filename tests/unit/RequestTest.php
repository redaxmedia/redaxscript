<?php
namespace Redaxscript\Tests;

/**
 * RequestTest
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Request
 */

class RequestTest extends TestCaseAbstract
{
	/**
	 * testInit
	 *
	 * @since 3.0.0
	 */

	public function testInit() : void
	{
		/* setup */

		$this->_request->init();

		/* actual */

		$actual = $this->_request;

		/* compare */

		$this->assertInstanceOf('Redaxscript\Request', $actual);
	}

	/**
	 * testGet
	 *
	 * @since 4.0.0
	 */

	public function testGet() : void
	{
		/* actual */

		$actualArray = $this->_request->get('server');

		/* compare */

		$this->assertArrayHasKey('argv', $actualArray);
	}

	/**
	 * testGetArray
	 *
	 * @since 4.0.0
	 */

	public function testGetArray() : void
	{
		/* actual */

		$actualArray = $this->_request->getArray();

		/* compare */

		$this->assertArrayHasKey('server', $actualArray);
		$this->assertArrayHasKey('get', $actualArray);
		$this->assertArrayHasKey('post', $actualArray);
		$this->assertArrayHasKey('files', $actualArray);
		$this->assertArrayHasKey('session', $actualArray);
		$this->assertArrayHasKey('cookie', $actualArray);
	}

	/**
	 * testGlobal
	 *
	 * @since 2.2.0
	 */

	public function testGlobal() : void
	{
		/* setup */

		$this->_request->set('testKey', 'testValue');

		/* actual */

		$actual = $this->_request->get('testKey');

		/* compare */

		$this->assertEquals('testValue', $actual);
	}

	/**
	 * testServer
	 *
	 * @since 2.2.0
	 */

	public function testServer() : void
	{
		/* setup */

		$this->_request->setServer('testKey', 'testValue');

		/* actual */

		$actual = $this->_request->getServer('testKey');

		/* compare */

		$this->assertEquals('testValue', $actual);
	}

	/**
	 * testQuery
	 *
	 * @since 2.2.0
	 */

	public function testQuery() : void
	{
		/* setup */

		$this->_request->setQuery('testKey', 'testValue');

		/* actual */

		$actual = $this->_request->getQuery('testKey');

		/* compare */

		$this->assertEquals('testValue', $actual);
	}

	/**
	 * testPost
	 *
	 * @since 2.2.0
	 */

	public function testPost() : void
	{
		/* setup */

		$this->_request->setPost('testKey', 'testValue');

		/* actual */

		$actual = $this->_request->getPost('testKey');

		/* compare */

		$this->assertEquals('testValue', $actual);
	}

	/**
	 * testFiles
	 *
	 * @since 3.9.0
	 */

	public function testFiles() : void
	{
		/* setup */

		$this->_request->setFiles('testKey', 'testValue');

		/* actual */

		$actual = $this->_request->getFiles('testKey');

		/* compare */

		$this->assertEquals('testValue', $actual);
	}

	/**
	 * testSession
	 *
	 * @since 2.6.2
	 */

	public function testSession() : void
	{
		/* setup */

		$this->_request->setSession('testKey', 'testValue');
		$this->_request->refreshSession();

		/* actual */

		$actual = $this->_request->getSession('testKey');

		/* compare */

		$this->assertEquals('testValue', $actual);
	}

	/**
	 * testCookie
	 *
	 * @since 2.6.2
	 */

	public function testCookie() : void
	{
		/* setup */

		$this->_request->setCookie('testKey', 'testValue');
		$this->_request->refreshCookie();

		/* actual */

		$actual = $this->_request->getCookie('testKey');

		/* compare */

		$this->assertEquals('testValue', $actual);
	}

	/**
	 * testGetInvalid
	 *
	 * @since 3.0.0
	 */

	public function testGetInvalid() : void
	{
		/* actual */

		$actual = $this->_request->get('invalidKey');

		/* compare */

		$this->assertNull($actual);
	}
}
