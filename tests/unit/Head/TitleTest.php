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
 *
 * @covers Redaxscript\Head\HeadAbstract
 * @covers Redaxscript\Head\Title
 */

class TitleTest extends TestCaseAbstract
{
	/**
	 * testRender
	 *
	 * @since 3.0.0
	 *
	 * @param string $text
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
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
