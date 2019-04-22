<?php
namespace Redaxscript\Tests\Admin\View\Helper;

use Redaxscript\Admin\View\Helper;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * DockTest
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Admin\View\Helper\Dock
 */

class DockTest extends TestCaseAbstract
{
	/**
	 * testRender
	 *
	 * @since 4.0.0
	 *
	 * @param array $registryArray
	 * @param array $renderArray
	 * @param array $optionArray
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testRender(array $registryArray = [], array $renderArray = [], array $optionArray = [], string $expect = null) : void
	{
		/* setup */

		$this->_registry->init($registryArray);
		$adminDock = new Helper\Dock($this->_registry, $this->_language);
		$adminDock->init($optionArray);

		/* actual */

		$actual = $adminDock->render($renderArray['table'], $renderArray['id']);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
