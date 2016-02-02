<?php
namespace Redaxscript\Tests\Admin\View;

use Redaxscript\Admin;
use Redaxscript\Tests\TestCase;

/**
 * ModuleFormTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class ModuleFormTest extends TestCase
{
	/**
	 * providerRender
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerRender()
	{
		return $this->getProvider('tests/provider/Admin/View/module_form_render.json');
	}

	/**
	 * testRender
	 *
	 * @since 3.0.0
	 *
	 * @param array $expect
	 *
	 * @dataProvider providerRender
	 */

	public function testRender($expect = array())
	{
		/* setup */

		$moduleForm = new Admin\View\ModuleForm();

		/* actual */

		$actual = $moduleForm->render(1);

		/* compare */

		$this->assertStringStartsWith($expect['start'], $actual);
		$this->assertStringEndsWith($expect['end'], $actual);
	}
}
