<?php
namespace Redaxscript\Tests\View;

use Redaxscript\Tests\TestCaseAbstract;
use Redaxscript\View;

/**
 * ConsoleFormTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\View\ConsoleForm
 * @covers Redaxscript\View\ViewAbstract
 */

class ConsoleFormTest extends TestCaseAbstract
{
	/**
	 * testRender
	 *
	 * @since 3.0.0
	 *
	 * @param array $registryArray
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testRender(array $registryArray = [], string $expect = null)
	{
		/* setup */

		$this->_registry->init($registryArray);
		$consoleForm = new View\ConsoleForm($this->_registry, $this->_language);

		/* actual */

		$actual = $consoleForm->render();

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
