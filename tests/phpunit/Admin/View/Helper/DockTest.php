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
 */

class DockTest extends TestCaseAbstract
{

	/**
	 * providerRender
	 *
	 * @since 4.0.0
	 *
	 * @return array
	 */

	public function providerRender() : array
	{
		return $this->getProvider('tests/provider/Admin/View/Helper/dock_render.json');
	}

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
	 * @dataProvider providerRender
	 */

	public function testRender(array $registryArray = [], array $renderArray = [], array $optionArray = [], string $expect = null)
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
