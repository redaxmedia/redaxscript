<?php
namespace Redaxscript\Tests;
use Redaxscript\Helper;
use Redaxscript\Registry;

/**
 * HelperTest
 *
 * @since 2.1.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class HelperTest extends TestCase
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

	protected function setUp()
	{
		$this->_registry = Registry::getInstance();
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
		return $this->getProvider('tests/provider/helper_get_subset.json');
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
		return $this->getProvider('tests/provider/helper_get_class.json');
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
		$helper = new Helper($this->_registry);

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
		$helper = new Helper($this->_registry);

		/* result */

		$result = $helper->getClass();

		/* compare */

		$this->assertEquals($expect, $result);
	}
}
