<?php
namespace Redaxscript\Tests\Admin\View;

use Redaxscript\Admin;
use Redaxscript\Tests\TestCase;

/**
 * ArticleFormTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class ArticleFormTest extends TestCase
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
		return $this->getProvider('tests/provider/Admin/View/article_form_render.json');
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

		$login = new Admin\View\ArticleForm();

		/* actual */

		$actual = $login->render();

		/* compare */

		$this->assertStringStartsWith($expect['start'], $actual);
		$this->assertStringEndsWith($expect['end'], $actual);
	}
}
