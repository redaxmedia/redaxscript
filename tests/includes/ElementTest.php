<?php
namespace Redaxscript\Tests;

use Redaxscript\Element;

/**
 * HelperTest
 *
 * @since 2.2.0
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
		return $this->getProvider('tests/provider/element_create.json');
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
		return $this->getProvider('tests/provider/element_attr.json');
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
		return $this->getProvider('tests/provider/element_class.json');
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

		$element = new Element($tag, $attributeArray);

		/* result */

		$result = $element;

		/* compare */

		$this->assertEquals($expect, $result);
	}

	/**
	 * testCopy
	 *
	 * @since 2.2.0
	 */

	public function testCopy()
	{
		/* setup */

		$element = new Element('a');
		$elementCopy = $element->copy()->attr('href', 'test');

		/* expect and result */

		$expect = $element;
		$result = $elementCopy;

		/* compare */

		$this->assertNotEquals($expect, $result);
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

		$element = new Element('a');

		/* result */

		$result = $element->attr($attribute[0], $attribute[1])->removeAttr($attribute[2])->render();

		/* compare */

		$this->assertEquals($expect, $result);
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

		$element = new Element('a');

		/* result */

		$result = $element->addClass($className[0])->removeClass($className[1])->render();

		/* compare */

		$this->assertEquals($expect, $result);
	}

	/**
	 * testVal
	 *
	 * @since 2.2.0
	 */

	public function testVal()
	{
		/* setup */

		$element = new Element('input');

		/* expect and result */

		$expect = '<input value="test" />';
		$result = $element->val('test');

		/* compare */

		$this->assertEquals($expect, $result);
	}

	/**
	 * testHtml
	 *
	 * @since 2.2.0
	 */

	public function testHtml()
	{
		/* setup */

		$element = new Element('a');

		/* expect and result */

		$expect = '<a><span>test</span></a>';
		$result = $element->html('<span>test</span>');

		/* compare */

		$this->assertEquals($expect, $result);
	}

	/**
	 * testText
	 *
	 * @since 2.2.0
	 */

	public function testText()
	{
		/* setup */

		$element = new Element('a');

		/* expect and result */

		$expect = '<a>test</a>';
		$result = $element->text('<span>test</span>');

		/* compare */

		$this->assertEquals($expect, $result);
	}

	/**
	 * testClean
	 *
	 * @since 2.2.0
	 */

	public function testClean()
	{
		/* setup */

		$element = new Element('a');
		$element->text('test');

		/* expect and result */

		$expect = '<a></a>';
		$result = $element->clean();

		/* compare */

		$this->assertEquals($expect, $result);
	}
}
