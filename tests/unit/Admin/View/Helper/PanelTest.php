<?php
namespace Redaxscript\Tests\Admin\View\Helper;

use Redaxscript\Admin\View\Helper;
use Redaxscript\Module;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * PanelTest
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Admin\View\Helper\Panel
 */

class PanelTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 4.0.0
	 */

	public function setUp() : void
	{
		parent::setUp();
		$this->createDatabase();
		$this->installTestDummy();
	}

	/**
	 * tearDown
	 *
	 * @since 4.0.0
	 */

	public function tearDown() : void
	{
		$this->uninstallTestDummy();
		$this->dropDatabase();
	}

	/**
	 * testRender
	 *
	 * @since 4.0.0
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

		Module\Hook::construct($this->_registry, $this->_request, $this->_language, $this->_config);
		Module\Hook::init();
		$this->_registry->init($registryArray);
		$adminPanel = new Helper\Panel($this->_registry, $this->_language);
		$adminPanel->init($optionArray);

		/* actual */

		$actual = $adminPanel->render();

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
