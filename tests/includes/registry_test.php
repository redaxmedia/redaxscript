<?php

/**
 * Redaxscript Registry Test
 *
 * @since 2.1.0
 *
 * @package Redaxscript
 * @category Tests
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

	private $_registry;

	/**
	 * setUp
	 *
	 * @since 2.1.0
	 */

	protected function setUp()
	{
		$this->_registry = Redaxscript_Registry::instance();
		$this->_registry->init();
	}

	/**
	 * testSetAndGet
	 *
	 * @since 2.1.0
	 */

	public function testSetAndGet()
	{
		/* setup */

		$this->_registry->set('testKey', 'testValue');

		/* result */

		$result = $this->_registry->get('testKey');

		/* compare */

		$this->assertEquals('testValue', $result);
	}
}
?>