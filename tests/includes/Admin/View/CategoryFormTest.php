<?php
namespace Redaxscript\Tests\Admin\View;

use Redaxscript\Admin;
use Redaxscript\Tests\TestCase;

/**
 * CategoryFormTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class CategoryFormTest extends TestCase
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
		return $this->getProvider('tests/provider/Admin/View/category_form_render.json');
	}

	/**
	 * testRender
	 *
	 * @since 3.0.0
	 *
	 * @param integer $categoryId
	 * @param array $expect
	 *
	 * @dataProvider providerRender
	 */

	public function testRender($categoryId = null, $expect = array())
	{
		/* setup */

		$categoryForm = new Admin\View\CategoryForm();

		/* actual */

		$actual = $categoryForm->render($categoryId);

		/* compare */

		$this->assertStringStartsWith($expect['start'], $actual);
		$this->assertStringEndsWith($expect['end'], $actual);
	}
}
