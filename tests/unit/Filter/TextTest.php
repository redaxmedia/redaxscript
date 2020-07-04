<?php
namespace Redaxscript\Tests\Filter;

use Redaxscript\Filter;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * TextTest
 *
 * @since 4.3.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Filter\Text
 */

class TextTest extends TestCaseAbstract
{
	/**
	 * testSanitize
	 *
	 * @since 4.3.0
	 *
	 * @param int|string $text
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testSanitize($text = null, string $expect = null) : void
	{
		/* setup */

		$filter = new Filter\Text();

		/* actual */

		$actual = $filter->sanitize($text);

		/* compare */

		$this->assertSame($expect, $actual);
	}
}
