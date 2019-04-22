<?php
namespace Redaxscript\Tests\Html;

use Redaxscript\Html;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * HtmlTest
 *
 * @since 2.6.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Html\HtmlAbstract
 */

class HtmlTest extends TestCaseAbstract
{
	/**
	 * testHtml
	 *
	 * @since 2.6.0
	 *
	 * @param string $html
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testHtml(string $html = null, string $expect = null) : void
	{
		/* setup */

		$element = new Html\Element();
		$element->init('a');

		/* actual */

		$actual = $element->html($html);

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testAppend
	 *
	 * @since 2.6.0
	 *
	 * @param string $html
	 * @param string $append
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testAppend(string $html = null, string $append = null, string $expect = null) : void
	{
		/* setup */

		$element = new Html\Element();
		$element->init('div')->html($html);

		/* actual */

		$actual = $element->append($append);

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testPrepend
	 *
	 * @since 2.6.0
	 *
	 * @param string $html
	 * @param string $prepend
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testPrepend(string $html = null, string $prepend = null, string $expect = null) : void
	{
		/* setup */

		$element = new Html\Element();
		$element->init('div')->html($html);

		/* actual */

		$actual = $element->prepend($prepend);

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testClear
	 *
	 * @since 3.0.0
	 */

	public function testClear() : void
	{
		/* setup */

		$element = new Html\Element();
		$element->init('a');

		/* expect and actual */

		$expect = '<a></a>';
		$actual = $element->text('test')->clear();

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
