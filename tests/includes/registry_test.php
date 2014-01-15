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
	 * Gets an instance of the regisrty class to be used in each test
	 *
	 * @since 2.1.0
	 */
	protected function setUp()
	{
		$this->_registry = Redaxscript_Registry::getInstance();
		$this->_registry->init(array());
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


	/**
	 * testClear
	 *
	 * Test for the Clear method
	 *
	 * @since 2.1.0
	 */
	public function testClear()
	{
		$this->_registry->set('testKey', 'testValue');
		$result = $this->_registry->get('testKey');
		/* test result */
		$this->assertEquals('testValue', $result);
		$this->_registry->clear('testKey');
		$result = $this->_registry->get('testKey');
		/* test result */
		$this->assertEquals(null, $result);
	}


	/**
	 * testExists
	 *
	 * Test for the Exists method
	 *
	 * @since 2.1.0
	 */
	public function testExists()
	{
		$result = $this->_registry->exists('testKey');
		$this->assertFalse($result);
		$this->_registry->set('testKey', 'testValue');
		$result = $this->_registry->exists('testKey');
		$this->assertTrue($result);
	}

}

?>