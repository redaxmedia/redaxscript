<?php
namespace Redaxscript\Tests\Html;

use Redaxscript\Html;
use Redaxscript\Tests\TestCase;

/**
 * HtmlTest
 *
 * @since 2.6.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class HtmlTest extends TestCase
{
	/**
	 * providerHtml
	 *
	 * @since 2.6.0
	 *
	 * @return array
	 */

	public function providerHtml()
	{
		return $this->getProvider('tests/provider/Html/html_html.json');
	}

	/**
	 * providerAppend
	 *
	 * @since 2.6.0
	 *
	 * @return array
	 */

	public function providerAppend()
	{
		return $this->getProvider('tests/provider/Html/html_append.json');
	}

	/**
	 * providerPrepend
	 *
	 * @since 2.6.0
	 *
	 * @return array
	 */

	public function providerPrepend()
	{
		return $this->getProvider('tests/provider/Html/html_prepend.json');
	}

	/**
	 * providerClean
	 *
	 * @since 2.6.0
	 *
	 * @return array
	 */

	public function providerClean()
	{
		return $this->getProvider('tests/provider/Html/html_clean.json');
	}

	/**
	 * testHtml
	 *
	 * @since 2.6.0
	 *
	 * @param string $html
	 * @param string $expect
	 *
	 * @dataProvider providerHtml
	 */

	public function testHtml($html = null, $expect = null)
	{
		/* setup */

		$element = new Html\Element('a');

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
	 * @dataProvider providerAppend
	 */

	public function testAppend($html = null, $append = null, $expect = null)
	{
		/* setup */

		$element = new Html\Element('div');
		$element->html($html);

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
	 * @dataProvider providerPrepend
	 */

	public function testPrepend($html = null, $prepend = null, $expect = null)
	{
		/* setup */

		$element = new Html\Element('div');
		$element->html($html);

		/* actual */

		$actual = $element->prepend($prepend);

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testClean
	 *
	 * @since 2.6.0
	 */

	public function testClean()
	{
		/* setup */

		$element = new Html\Element('a');

		/* expect and actual */

		$expect = '<a></a>';
		$actual = $element->text('test')->clean();

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
