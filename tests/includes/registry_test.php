<?php

/**
 * registry test
 *
 * @since 2.1.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Gary Aylward
 */

/* Include stubs */
include_once (dirname(__FILE__) . '/../stubs.php');

/**
 * Redaxscript_Registry_Test
 *
 * @since 2.1.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Gary Aylward
 */

class Redaxscript_Registry_Test extends PHPUnit_Framework_TestCase
{

	private $_registry;

	/**
	 * setUp
	 *
	 * Gets an instance of the registry class to be used in each test
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
	 * Test for the Set and Get methods
	 *
	 * @since 2.1.0
	 */

	public function testSetAndGet()
	{
		$this->_registry->set('testKey', 'testValue');
		$result = $this->_registry->get('testKey');
		/* test result */
		$this->assertEquals('testValue', $result);
	}
}

?>