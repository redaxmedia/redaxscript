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
 *
 * @covers Redaxscript\Head\HeadAbstract
 * @covers Redaxscript\Head\Style
 */

class StyleTest extends TestCaseAbstract
{
	/**
	 * testInline
	 *
	 * @since 3.0.0
	 *
	 * @param string $append
	 * @param string $prepend
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testInline(string $append = null, string $prepend = null, string $expect = null)
	{
		/* setup */

		$style = Head\Style::getInstance();
		$style
			->appendInline($append)
			->prependInline($prepend);

		/* actual */

		$actual = $style;

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
