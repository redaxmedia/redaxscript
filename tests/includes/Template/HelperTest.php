<?php
namespace Redaxscript\Tests\Template;

use Redaxscript\Registry;
use Redaxscript\Template;
use Redaxscript\Tests\TestCase;

/**
 * HelperTest
 *
 * @since 3.0.0
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
	 * @since 3.0.0
	 */

	protected function setUp()
	{
		$this->_registry = Registry::getInstance();
	}

	/**
	 * providerGetSubset
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerGetSubset()
	{
		return $this->getProvider('tests/provider/Template/helper_get_subset.json');
	}

	/**
	 * providerGetDirection
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerGetDirection()
	{
		return $this->getProvider('tests/provider/Template/helper_get_direction.json');
	}

	/**
	 * providerGetDirection
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerGetClass()
	{
		return $this->getProvider('tests/provider/Template/helper_get_class.json');
	}

	/**
	 * testGetSubset
	 *
	 * @since 3.0.0
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

		/* actual */

		$actual = Template\Helper::getSubset();

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testGetDirection
	 *
	 * @since 3.0.0
	 *
	 * @param array $registry
	 * @param string $expect
	 *
	 * @dataProvider providerGetDirection
	 */

	public function testGetDirection($registry = array(), $expect = null)
	{
		/* setup */

		$this->_registry->init($registry);

		/* actual */

		$actual = Template\Helper::getDirection();

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testGetClass
	 *
	 * @since 3.0.0
	 *
	 * @param array $registry
	 * @param string $expect
	 *
	 * @dataProvider providerGetClass
	 */

	public function testGetClass($registry = array(), $expect = null)
	{
		/* setup */

		$this->_registry->init($registry);

		/* actual */

		$actual = Template\Helper::getClass();

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
