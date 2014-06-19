<?php

/**
 * Redaxscript Registry Test
 *
 * @since 2.1.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 * @author Gary Aylward
 */

class Redaxscript_Registry_Test extends PHPUnit_Framework_TestCase
{
	/**
	 * registry
	 *
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

	protected function setUp()
	{
		$this->_registry = Redaxscript_Registry::getInstance();
		$this->_registry->init();
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

		/* result */

		$result = $this->_registry->get('testKey');

		/* compare */

		$this->assertEquals('testValue', $result);
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

		/* result */

		$result = $this->_registry->get();

		/* compare */

		$this->assertArrayHasKey('testAll', $result);
	}

	/**
	 * testGetInvalid
	 *
	 * @since 2.1.0
	 */

	public function testGetInvalid()
	{
		/* result */

		$result = $this->_registry->get('invalidKey');

		/* compare */

		$this->assertEquals(null, $result);
	}

	/**
	 * testReset
	 *
	 * @since 2.1.0
	 */

	public function testReset()
	{
		/* setup */

		$this->_registry->reset();

		/* result */

		$result = $this->_registry->getInstance();

		/* compare */

		$this->assertInstanceOf('Redaxscript_Registry', $result);
	}
}
