<?php
namespace Redaxscript\Tests;

/**
 * RegistryTest
 *
 * @since 2.1.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 * @author Gary Aylward
 *
 * @covers Redaxscript\Registry
 */

class RegistryTest extends TestCaseAbstract
{
	/**
	 * testInit
	 *
	 * @since 3.0.0
	 */

	public function testInit() : void
	{
		/* setup */

		$this->_registry->init();

		/* actual */

		$actual = $this->_registry;

		/* compare */

		$this->assertInstanceOf('Redaxscript\Registry', $actual);
	}

	/**
	 * testGetAndSet
	 *
	 * @since 2.1.0
	 */

	public function testGetAndSet() : void
	{
		/* setup */

		$this->_registry->set('testKey', 'testValue');

		/* actual */

		$actual = $this->_registry->get('testKey');

		/* compare */

		$this->assertEquals('testValue', $actual);
	}

	/**
	 * testGetArray
	 *
	 * @since 4.0.0
	 */

	public function testGetArray() : void
	{
		/* setup */

		$this->_registry->set('testKey', 'testValue');

		/* actual */

		$actualArray = $this->_registry->getArray();

		/* compare */

		$this->assertArrayHasKey('testKey', $actualArray);
	}

	/**
	 * testGetInvalid
	 *
	 * @since 2.1.0
	 */

	public function testGetInvalid() : void
	{
		/* actual */

		$actual = $this->_registry->get('invalidKey');

		/* compare */

		$this->assertNull($actual);
	}
}
