<?php
namespace Redaxscript\Tests\Admin\View\Helper;

use Redaxscript\Admin\View\Helper;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * ControlTest
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Admin\View\Helper\Control
 */

class ControlTest extends TestCaseAbstract
{
	/**
	 * testRender
	 *
	 * @since 4.0.0
	 *
	 * @param array $registryArray
	 * @param array $renderArray
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testRender(array $registryArray = [], array $renderArray = [], string $expect = null) : void
	{
		/* setup */

		$this->_registry->init($registryArray);
		$adminControl = new Helper\Control($this->_registry, $this->_language);

		/* actual */

		$actual = $adminControl->render($renderArray['table'], $renderArray['id'], $renderArray['alias'], $renderArray['status']);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
