<?php
namespace Redaxscript\Tests\Head;

use Redaxscript\Head;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * TitleTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Balázs Szilágyi
 */

class TitleTest extends TestCaseAbstract
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
		return $this->getProvider('tests/provider/Head/title_render.json');
	}

	/**
	 * testRender
	 *
	 * @since 3.0.0
	 *
	 * @param string $text
	 * @param string $expect
	 *
	 * @dataProvider providerRender
	 */

	public function testRender(string $text= null, string $expect = null)
	{
		/* setup */

		$title = new Head\Title($this->_registry);

		/* actual */

		$actual = $title->render($text);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
