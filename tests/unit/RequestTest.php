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
	 * testGetAndSet
	 *
	 * @since 4.0.0
	 */

	public function testGetAndSet() : void
	{
		/* setup */

		$this->_request->set('testKey',
		[
			'testValue'
		]);

		/* actual */

		$actualArray = $this->_request->get('testKey');

		/* compare */

		$this->assertContains('testValue', $actualArray);
	}

	/**
	 * testGetInvalid
	 *
	 * @since 4.0.0
	 */

	public function testGetInvalid() : void
	{
		/* actual */

		$actual = $this->_request->get('invalid');

		/* compare */

		$this->assertNull($actual);
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
		$this->assertArrayHasKey('stream', $actualArray);
		$this->assertArrayHasKey('session', $actualArray);
		$this->assertArrayHasKey('cookie', $actualArray);
	}

	/**
	 * testGetAndSetServer
	 *
	 * @since 2.2.0
	 */

	public function testGetAndSetServer() : void
	{
		/* setup */

		$this->_request->setServer('testKey', 'testValue');

		/* actual */

		$actual = $this->_request->getServer('testKey');

		/* compare */

		$this->assertEquals('testValue', $actual);
	}

	/**
	 * testGetServerInvalid
	 *
	 * @since 4.0.0
	 */

	public function testGetServerInvalid() : void
	{
		/* actual */

		$actual = $this->_request->getServer('invalid');

		/* compare */

		$this->assertNull($actual);
	}

	/**
	 * testGetAndSetQuery
	 *
	 * @since 2.2.0
	 */

	public function testGetAndSetQuery() : void
	{
		/* setup */

		$this->_request->setQuery('testKey', 'testValue');

		/* actual */

		$actual = $this->_request->getQuery('testKey');

		/* compare */

		$this->assertEquals('testValue', $actual);
	}

	/**
	 * testGetQueryInvalid
	 *
	 * @since 4.0.0
	 */

	public function testGetQueryInvalid() : void
	{
		/* actual */

		$actual = $this->_request->getQuery('invalid');

		/* compare */

		$this->assertNull($actual);
	}

	/**
	 * testGetAndSetPost
	 *
	 * @since 2.2.0
	 */

	public function testGetAndSetPost() : void
	{
		/* setup */

		$this->_request->setPost('testKey', 'testValue');

		/* actual */

		$actual = $this->_request->getPost('testKey');

		/* compare */

		$this->assertEquals('testValue', $actual);
	}

	/**
	 * testGetPostInvalid
	 *
	 * @since 4.0.0
	 */

	public function testGetPostInvalid() : void
	{
		/* actual */

		$actual = $this->_request->getPost('invalid');

		/* compare */

		$this->assertNull($actual);
	}

	/**
	 * testGetAndSetFiles
	 *
	 * @since 3.9.0
	 */

	public function testGetAndSetFiles() : void
	{
		/* setup */

		$this->_request->setFiles('testKey', 'testValue');

		/* actual */

		$actual = $this->_request->getFiles('testKey');

		/* compare */

		$this->assertEquals('testValue', $actual);
	}

	/**
	 * testGetFilesInvalid
	 *
	 * @since 4.0.0
	 */

	public function testGetFilesInvalid() : void
	{
		/* actual */

		$actual = $this->_request->getFiles('invalid');

		/* compare */

		$this->assertNull($actual);
	}

	/**
	 * testGetAndSetServer
	 *
	 * @since 4.2.0
	 */

	public function testGetAndSetStream() : void
	{
		/* setup */

		$this->_request->setStream('testKey', 'testValue');

		/* actual */

		$actual = $this->_request->getStream('testKey');

		/* compare */

		$this->assertEquals('testValue', $actual);
	}

	/**
	 * testGetStreamInvalid
	 *
	 * @since 4.2.0
	 */

	public function testGetStreamInvalid() : void
	{
		/* actual */

		$actual = $this->_request->getStream('invalid');

		/* compare */

		$this->assertNull($actual);
	}

	/**
	 * testGetAndSetAndRefreshSession
	 *
	 * @since 2.6.2
	 */

	public function testGetAndSetAndRefreshSession() : void
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
	 * testGetSessionInvalid
	 *
	 * @since 4.0.0
	 */

	public function testGetSessionInvalid() : void
	{
		/* actual */

		$actual = $this->_request->getSession('invalid');

		/* compare */

		$this->assertNull($actual);
	}

	/**
	 * testGetAndSetAndRefreshCookie
	 *
	 * @since 2.6.2
	 */

	public function testGetAndSetAndRefreshCookie() : void
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
	 * testGetCookieInvalid
	 *
	 * @since 4.0.0
	 */

	public function testGetCookieInvalid() : void
	{
		/* actual */

		$actual = $this->_request->getCookie('invalid');

		/* compare */

		$this->assertNull($actual);
	}
}
