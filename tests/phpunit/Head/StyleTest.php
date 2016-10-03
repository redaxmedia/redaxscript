<?php
namespace Redaxscript\Tests\Head;

use Redaxscript\Head;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * StyleTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Balázs Szilágyi
 */

class StyleTest extends TestCaseAbstract
{
	/**
	 * providerInline
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerInline()
	{
		return $this->getProvider('tests/provider/Head/style_inline.json');
	}

	/**
	 * testRender
	 *
	 * @since 3.0.0
	 *
	 * @param string $append
	 * @param string $prepend
	 * @param string $expect
	 *
	 * @dataProvider providerInline
	 */

	public function testRender($append = null, $prepend = null, $expect = null)
	{
		/* setup */

		$style = Head\Style::getInstance();
		$style
			->appendInline($append)
			->prependInline($prepend);

		/* actual */

		$actual = $style->render();

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
