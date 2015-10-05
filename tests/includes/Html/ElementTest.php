<?php
namespace Redaxscript\Tests\Html;

use Redaxscript\Html;
use Redaxscript\Tests\TestCase;

/**
 * ElementTest
 *
 * @since 2.6.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class ElementTest extends TestCase
{
	/**
	 * providerCreate
	 *
	 * @since 2.2.0
	 *
	 * @return array
	 */

	public function providerCreate()
	{
		return $this->getProvider('tests/provider/Html/element_create.json');
	}

	/**
	 * providerAttr
	 *
	 * @since 2.2.0
	 *
	 * @return array
	 */

	public function providerAttr()
	{
		return $this->getProvider('tests/provider/Html/element_attr.json');
	}

	/**
	 * providerClass
	 *
	 * @since 2.2.0
	 *
	 * @return array
	 */

	public function providerClass()
	{
		return $this->getProvider('tests/provider/Html/element_class.json');
	}

	/**
	 * providerVal
	 *
	 * @since 2.6.0
	 *
	 * @return array
	 */

	public function providerVal()
	{
		return $this->getProvider('tests/provider/Html/element_val.json');
	}

	/**
	 * providerText
	 *
	 * @since 2.6.0
	 *
	 * @return array
	 */

	public function providerText()
	{
		return $this->getProvider('tests/provider/Html/element_text.json');
	}

	/**
	 * testCreate
	 *
	 * @since 2.2.0
	 *
	 * @param string $tag
	 * @param array $attributeArray
	 * @param string $expect
	 *
	 * @dataProvider providerCreate
	 */

	public function testCreate($tag = null, $attributeArray = array(), $expect = null)
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
	 * @param array $attribute
	 * @param string $expect
	 *
	 * @dataProvider providerAttr
	 */

	public function testAttr($attribute = array(), $expect = null)
	{
		/* setup */

		$element = new Html\Element();
		$element->init('a');

		/* actual */

		$actual = $element->attr($attribute[0], $attribute[1])->removeAttr($attribute[2])->render();

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testClass
	 *
	 * @since 2.2.0
	 *
	 * @param array $className
	 * @param string $expect
	 *
	 * @dataProvider providerClass
	 */

	public function testClass($className = array(), $expect = null)
	{
		/* setup */

		$element = new Html\Element();
		$element->init('a');

		/* actual */

		$actual = $element->addClass($className[0])->removeClass($className[1])->render();

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testVal
	 *
	 * @since 2.6.0
	 *
	 * @param array $value
	 * @param string $expect
	 *
	 * @dataProvider providerVal
	 */

	public function testVal($value = null, $expect = null)
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
	 * @dataProvider providerText
	 */

	public function testText($text = null, $expect = null)
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
