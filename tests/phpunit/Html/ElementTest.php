<?php
namespace Redaxscript\Tests\Html;

use Redaxscript\Html;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * ElementTest
 *
 * @since 2.6.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Html\Element
 */

class ElementTest extends TestCaseAbstract
{
	/**
	 * testCreate
	 *
	 * @since 2.2.0
	 *
	 * @param string $tag
	 * @param array $attributeArray
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testCreate(string $tag = null, array $attributeArray = [], string $expect = null)
	{
		/* setup */

		$element = new Html\Element();
		$element->init($tag, $attributeArray);

		/* actual */

		$actual = $element;

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testCopy
	 *
	 * @since 2.2.0
	 */

	public function testCopy()
	{
		/* setup */

		$element = new Html\Element();
		$element->init('a');
		$elementCopy = $element->copy()->attr('href', 'test');

		/* expect and actual */

		$expect = $element;
		$actual = $elementCopy;

		/* compare */

		$this->assertNotEquals($expect, $actual);
	}

	/**
	 * testAttr
	 *
	 * @since 2.2.0
	 *
	 * @param array $attributeArray
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testAttr(array $attributeArray = [], string $expect = null)
	{
		/* setup */

		$element = new Html\Element();
		$element->init('a');

		/* actual */

		$actual = $element->attr($attributeArray[0], $attributeArray[1])->removeAttr($attributeArray[2])->render();

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testClass
	 *
	 * @since 2.2.0
	 *
	 * @param array $classNameArray
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testClass(array $classNameArray = [], string $expect = null)
	{
		/* setup */

		$element = new Html\Element();
		$element->init('a');

		/* actual */

		$actual = $element->addClass($classNameArray[0])->addClass($classNameArray[1])->removeClass($classNameArray[2])->render();

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testVal
	 *
	 * @since 2.6.0
	 *
	 * @param string $value
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testVal(string $value = null, string $expect = null)
	{
		/* setup */

		$element = new Html\Element();
		$element->init('input');

		/* actual */

		$actual = $element->val($value);

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testText
	 *
	 * @since 2.6.0
	 *
	 * @param array $text
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testText($text = null, string $expect = null)
	{
		/* setup */

		$element = new Html\Element();
		$element->init('a');

		/* actual */

		$actual = $element->text($text);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
