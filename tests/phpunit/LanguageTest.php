<?php
namespace Redaxscript\Tests;

/**
 * LanguageTest
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Language
 */

class LanguageTest extends TestCaseAbstract
{
	/**
	 * testInit
	 *
	 * @since 3.0.0
	 */

	public function testInit()
	{
		/* setup */

		$this->_language->init('de');
		$this->_language->init('en');

		/* actual */

		$actual = $this->_language;

		/* compare */

		$this->assertInstanceOf('Redaxscript\Language', $actual);
	}

	/**
	 * testGetAndSet
	 *
	 * @since 2.4.0
	 */

	public function testGetAndSet()
	{
		/* setup */

		$this->_language->set('testKey', 'testValue');

		/* actual */

		$actual = $this->_language->get('testKey');

		/* compare */

		$this->assertEquals('testValue', $actual);
	}

	/**
	 * testGetIndex
	 *
	 * @since 2.2.0
	 */

	public function testGetIndex()
	{
		/* actual */

		$actual = $this->_language->get('name', '_package');

		/* compare */

		$this->assertEquals('Redaxscript', $actual);
	}

	/**
	 * testGetAll
	 *
	 * @since 2.2.0
	 */

	public function testGetAll()
	{
		/* actual */

		$actualArray = $this->_language->get();

		/* compare */

		$this->assertArrayHasKey('home', $actualArray);
	}

	/**
	 * testGetInvalid
	 *
	 * @since 2.2.0
	 */

	public function testGetInvalid()
	{
		/* actual */

		$actual = $this->_language->get('invalidKey');

		/* compare */

		$this->assertFalse($actual);
	}
}
