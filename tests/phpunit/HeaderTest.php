<?php
namespace Redaxscript\Tests;

use Redaxscript\Header;

/**
 * HeaderTest
 *
 * @since 3.3.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Header
 */

class HeaderTest extends TestCaseAbstract
{
	/**
	 * testInit
	 *
	 * @since 3.3.0
	 *
	 * @runInSeparateProcess
	 */

	public function testInit()
	{
		/* setup */

		Header::init();

		/* expect and actual */

		$expectArray =
		[
			'X-Content-Type-Options: nosniff',
			'X-Frame-Options: sameorigin',
			'X-XSS-Protection: 1; mode=block'
		];
		$actualArray = $this->getHeaderArray();

		/* compare */

		$this->assertEquals($expectArray, $actualArray);
	}

	/**
	 * testAdd
	 *
	 * @since 3.3.0
	 *
	 * @runInSeparateProcess
	 */

	public function testAdd()
	{
		/* setup */

		Header::add('X-One: One');
		Header::add(
		[
			'X-Two: Two',
			'X-Three: Three'
		]);

		/* expect and actual */

		$expectArray =
		[
			'X-One: One',
			'X-Two: Two',
			'X-Three: Three'
		];
		$actualArray = $this->getHeaderArray();

		/* compare */

		$this->assertEquals($expectArray, $actualArray);
	}

	/**
	 * testAddInvalid
	 *
	 * @since 3.3.0
	 */

	public function testAddInvalid()
	{
		/* actual */

		$actual = Header::add();

		/* compare */

		$this->assertFalse($actual);
	}

	/**
	 * testRemoveInvalid
	 *
	 * @since 3.3.0
	 *
	 */

	public function testRemoveInvalid()
	{
		/* actual */

		$actual = Header::remove();

		/* compare */

		$this->assertFalse($actual);
	}

	/**
	 * testResponseCode
	 *
	 * @since 4.0.0
	 *
	 * @runInSeparateProcess
	 */

	public function testResponseCode()
	{
		/* actual */

		$actual = Header::responseCode(404);

		/* compare */

		$this->assertNumber($actual);
	}

	/**
	 * testDoRedirect
	 *
	 * @since 3.3.0
	 *
	 * @runInSeparateProcess
	 */

	public function testDoRedirect()
	{
		/* setup */

		Header::doRedirect('install.php');

		/* expect and actual */

		$expectArray =
		[
			'Location: install.php'
		];
		$actualArray = $this->getHeaderArray();

		/* compare */

		$this->assertEquals($expectArray, $actualArray);
	}

	/**
	 * testContentType
	 *
	 * @since 3.3.0
	 *
	 * @runInSeparateProcess
	 */

	public function testContentType()
	{
		/* setup */

		Header::contentType('application/json');

		/* expect and actual */

		$expectArray =
		[
			'Content-Type: application/json'
		];
		$actualArray = $this->getHeaderArray();

		/* compare */

		$this->assertEquals($expectArray, $actualArray);
	}
}
