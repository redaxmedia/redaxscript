<?php
namespace Redaxscript\Tests\Admin\View\Helper;

use Redaxscript\Admin\View\Helper;
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

	public function setUp()
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

	public function tearDown()
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

	public function testRender(array $registryArray = [], array $optionArray = [], string $expect = null)
	{
		/* setup */

		$this->_registry->init($registryArray);
		$adminPanel = new Helper\Panel($this->_registry, $this->_language);
		$adminPanel->init($optionArray);

		/* actual */

		$actual = $adminPanel->render();

		/* compare */

		$this->skipOnEnv('PARATEST');
		$this->assertEquals($expect, $actual);
	}
}
