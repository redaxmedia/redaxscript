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

		$element = new Element('a');
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

		$element = new Element('a');

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

		$element = new Element('a');

		/* actual */

		$actual = $element->addClass($className[0])->removeClass($className[1])->render();

		/* compare */

		$this->assertEquals($expect, $actual);
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

		/* expect and actual */

		$expect = '<input value="test" />';
		$actual = $element->val('test');

		/* compare */

		$this->assertEquals($expect, $actual);
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

		/* expect and actual */

		$expect = '<a><span>test</span></a>';
		$actual = $element->html('<span>test</span>');

		/* compare */

		$this->assertEquals($expect, $actual);
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

		/* expect and actual */

		$expect = '<a>test</a>';
		$actual = $element->text('<span>test</span>');

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testAppend
	 *
	 * @since 2.6.0
	 */

	public function testAppend()
	{
		/* setup */

		$element = new Element('div');
		$element->html('<span>test</span>');

		/* expect and actual */

		$expect = '<div><span>test</span><span>append</span></div>';
		$actual = $element->append('<span>append</span>');

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testPrepend
	 *
	 * @since 2.6.0
	 */

	public function testPrepend()
	{
		/* setup */

		$element = new Element('div');
		$element->html('<span>test</span>');

		/* expect and actual */

		$expect = '<div><span>prepend</span><span>test</span></div>';
		$actual = $element->prepend('<span>prepend</span>');

		/* compare */

		$this->assertEquals($expect, $actual);
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

		/* expect and actual */

		$expect = '<a></a>';
		$actual = $element->clean();

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
