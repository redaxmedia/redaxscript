<?php

/**
 * Redaxscript Helper Test
 *
 * @since 2.1.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class Redaxscript_Helper_Test extends PHPUnit_Framework_TestCase
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
	}

	/**
	 * providerGetSubset
	 *
	 * @since 2.1.0
	 *
	 * @return array
	 */

	public function providerGetSubset()
	{
		$contents = file_get_contents('tests/provider/helper_get_subset.json');
		$output = json_decode($contents, true);
		return $output;
	}

	/**
	 * providerGetClass
	 *
	 * @since 2.1.0
	 *
	 * @return array
	 */

	public function providerGetClass()
	{
		$contents = file_get_contents('tests/provider/helper_get_class.json');
		$output = json_decode($contents, true);
		return $output;
	}

	/**
	 * testGetSubset
	 *
	 * @since 2.1.0
	 *
	 * @param array $registry
	 * @param string $expect
	 *
	 * @dataProvider providerGetSubset
	 */

	public function testGetSubset($registry = array(), $expect = null)
	{
		/* setup */

		$this->_registry->init($registry);
		$helper = new Redaxscript_Helper($this->_registry);

		/* result */

		$result = $helper->getSubset();

		/* compare */

		$this->assertEquals($expect, $result);
	}

	/**
	 * testGetClass
	 *
	 * @since 2.1.0
	 *
	 * @param object $registry
	 * @param string $expect
	 *
	 * @dataProvider providerGetClass
	 */

	public function testGetClass($registry = null, $expect = null)
	{
		/* setup */

		$this->_registry->init($registry);
		$helper = new Redaxscript_Helper($this->_registry);

		/* result */

		$result = $helper->getClass();

		/* compare */

		$this->assertEquals($expect, $result);
	}
}
