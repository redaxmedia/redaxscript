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
 */

class ConsoleFormTest extends TestCaseAbstract
{
	/**
	 * providerRender
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerRender() : array
	{
		return $this->getProvider('tests/provider/View/console_form_render.json');
	}

	/**
	 * testRender
	 *
	 * @since 3.0.0
	 *
	 * @param array $registryArray
	 * @param string $expect
	 *
	 * @dataProvider providerRender
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
