<?php
namespace Redaxscript\Tests;

use Redaxscript\Registry;

/**
 * RegistryTest
 *
 * @since 2.1.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 * @author Gary Aylward
 */

class RegistryTest extends TestCaseAbstract
{
	/**
	 * instance of the registry class
	 *
	 * @var object
	 */

	protected $_registry;

	/**
	 * setUp
	 *
	 * @since 2.1.0
	 */

	public function setUp()
	{
		$this->_registry = Registry::getInstance();
	}

	/**
	 * testInit
	 *
	 * @since 3.0.0
	 */

	public function testInit()
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

	public function testGetAndSet()
	{
		/* setup */

		$this->_registry->set('testKey', 'testValue');

		/* actual */

		$actual = $this->_registry->get('testKey');

		/* compare */

		$this->assertEquals('testValue', $actual);
	}

	/**
	 * testGetAll
	 *
	 * @since 2.2.0
	 */

	public function testGetAll()
	{
		/* setup */

		$this->_registry->set('testAll', 'testAll');

		/* actual */

		$actual = $this->_registry->get();

		/* compare */

		$this->assertArrayHasKey('testAll', $actual);
	}

	/**
	 * testGetInvalid
	 *
	 * @since 2.1.0
	 */

	public function testGetInvalid()
	{
		/* actual */

		$actual = $this->_registry->get('invalidKey');

		/* compare */

		$this->assertFalse($actual);
	}
}
