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
	 * providerEdit
	 *
	 * @since 2.2.0
	 *
	 * @return array
	 */

	public function providerEdit()
	{
		return $this->getProvider('tests/provider/element_edit.json');
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
	 * testEdit
	 *
	 * @since 2.2.0
	 *
	 * @param string $attribute
	 * @param string $html
	 * @param string $text
	 * @param string $expect
	 *
	 * @dataProvider providerEdit
	 */

	public function testEdit($attribute = null, $html = null, $text = null, $expect = null)
	{
		/* setup */

		$element = new Element('a', array(
			'href' => 'test',
			'class' => 'link'
		));

		/* result */

		$result = $element->removeAttr($attribute)->html($html)->text($text);;

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
}
