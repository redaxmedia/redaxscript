<?php
namespace Redaxscript\Tests\Navigation;

use Redaxscript\Navigation;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * TemplateTest
 *
 * @since 3.3.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Navigation\Template
 * @covers Redaxscript\Navigation\NavigationAbstract
 */

class TemplateTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 3.3.0
	 */

	public function setUp() : void
	{
		parent::setUp();
		$optionArray = $this->getOptionArray();
		$installer = $this->installerFactory();
		$installer->init();
		$installer->rawCreate();
		$installer->insertSettings($optionArray);
	}

	/**
	 * tearDown
	 *
	 * @since 3.3.0
	 */

	public function tearDown() : void
	{
		$this->dropDatabase();
	}

	/**
	 * testRender
	 *
	 * @since 3.3.0
	 *
	 * @param array $registryArray
	 * @param array $optionArray
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testRender(array $registryArray = [], array $optionArray = [], string $expect = null) : void
	{
		/* setup */

		$this->_registry->init($registryArray);
		$navigation = new Navigation\Template($this->_registry, $this->_language);
		$navigation->init($optionArray);

		/* actual */

		$actual = $navigation;

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
