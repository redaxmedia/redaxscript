<?php
namespace Redaxscript\Tests\Html;

use Redaxscript\Html;
use Redaxscript\Language;
use Redaxscript\Registry;
use Redaxscript\Tests\TestCase;

/**
 * FormTest
 *
 * @since 2.6.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class FormTest extends TestCase
{
	/**
	 * instance of the registry class
	 *
	 * @var object
	 */

	protected $_registry;

	/**
	 * instance of the language class
	 *
	 * @var object
	 */

	protected $_language;

	/**
	 * setUp
	 *
	 * @since 2.6.0
	 */

	protected function setUp()
	{
		$this->_registry = Registry::getInstance();
		$this->_language = Language::getInstance();
	}

	/**
	 * providerCreate
	 *
	 * @since 2.6.0
	 *
	 * @return array
	 */

	public function providerCreate()
	{
		return $this->getProvider('tests/provider/Html/form_create.json');
	}

	/**
	 * providerLegend
	 *
	 * @since 2.6.0
	 *
	 * @return array
	 */

	public function providerLegend()
	{
		return $this->getProvider('tests/provider/Html/form_legend.json');
	}

	/**
	 * providerLabel
	 *
	 * @since 2.6.0
	 *
	 * @return array
	 */

	public function providerLabel()
	{
		return $this->getProvider('tests/provider/Html/form_label.json');
	}

	/**
	 * providerInput
	 *
	 * @since 2.6.0
	 *
	 * @return array
	 */

	public function providerInput()
	{
		return $this->getProvider('tests/provider/Html/form_input.json');
	}
	
	/**
	 * providerTextarea
	 *
	 * @since 2.6.0
	 *
	 * @return array
	 */

	public function providerTextarea()
	{
		return $this->getProvider('tests/provider/Html/form_textarea.json');
	}

	/**
	 * providerSelect
	 *
	 * @since 2.6.0
	 *
	 * @return array
	 */

	public function providerSelect()
	{
		return $this->getProvider('tests/provider/Html/form_select.json');
	}

	/**
	 * providerCaptcha
	 *
	 * @since 2.6.0
	 *
	 * @return array
	 */

	public function providerCaptcha()
	{
		return $this->getProvider('tests/provider/Html/form_captcha.json');
	}

	/**
	 * providerToken
	 *
	 * @since 2.6.0
	 *
	 * @return array
	 */

	public function providerToken()
	{
		return $this->getProvider('tests/provider/Html/form_token.json');
	}

	/**
	 * providerButton
	 *
	 * @since 2.6.0
	 *
	 * @return array
	 */

	public function providerButton()
	{
		return $this->getProvider('tests/provider/Html/form_button.json');
	}

	/**
	 * testCreate
	 *
	 * @param array $attributeArray
	 * @param array $options
	 * @param string $expect
	 *
	 * @dataProvider providerCreate
	 *
	 * @since 2.6.0
	 */

	public function testCreate($attributeArray = array(), $options = array(), $expect = null)
	{
		/* setup */

		$form = new Html\Form($this->_registry, $this->_language);
		$form->init($attributeArray, $options);

		/* actual */

		$actual = $form;

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testLegend
	 *
	 * @param string $text
	 * @param array $attributeArray
	 * @param array $expect
	 *
	 * @dataProvider providerLegend
	 *
	 * @since 2.6.0
	 */

	public function testLegend($text = null, $attributeArray = array(), $expect = array())
	{
		/* setup */

		$form = new Html\Form($this->_registry, $this->_language);
		$form->init();
		$form->legend($text, $attributeArray);

		/* actual */

		$actual = $form;

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testLabel
	 *
	 * @param string $text
	 * @param array $attributeArray
	 * @param array $expect
	 *
	 * @dataProvider providerLabel
	 *
	 * @since 2.6.0
	 */

	public function testLabel($text = null, $attributeArray = array(), $expect = array())
	{
		/* setup */

		$form = new Html\Form($this->_registry, $this->_language);
		$form->init();
		$form->label($text, $attributeArray);

		/* actual */

		$actual = $form;

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testInput
	 *
	 * @param string $method
	 * @param array $attributeArray
	 * @param array $expect
	 *
	 * @dataProvider providerInput
	 *
	 * @since 2.6.0
	 */

	public function testInput($method = null, $attributeArray = array(), $expect = array())
	{
		/* setup */

		$form = new Html\Form($this->_registry, $this->_language);
		$form->init();
		$form->$method($attributeArray);

		/* actual */

		$actual = $form->render();

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testTextarea
	 *
	 * @param array $attributeArray
	 * @param array $expect
	 *
	 * @dataProvider providerTextarea
	 *
	 * @since 2.6.0
	 */

	public function testTextarea($attributeArray = array(), $expect = array())
	{
		/* setup */

		$form = new Html\Form($this->_registry, $this->_language);
		$form->init();
		$form->textarea($attributeArray);

		/* actual */

		$actual = $form;

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testSelect
	 *
	 * @param array $optionArray
	 * @param array $attributeArray
	 * @param array $expect
	 *
	 * @dataProvider providerSelect
	 *
	 * @since 2.6.0
	 */

	public function testSelect($optionArray = array(), $attributeArray = array(), $expect = array())
	{
		/* setup */

		$form = new Html\Form($this->_registry, $this->_language);
		$form->init();
		$form->select($optionArray, $attributeArray);

		/* actual */

		$actual = $form;

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testCaptcha
	 *
	 * @param array $expect
	 *
	 * @dataProvider providerCaptcha
	 *
	 * @since 2.6.0
	 */

	public function testCaptcha($expect = array())
	{
		/* setup */

		$form = new Html\Form($this->_registry, $this->_language);
		$form->init(null, array(
			'captcha' => true
		));
		$form->captcha($expect['type']);

		/* actual */

		$actual = $form->render();

		/* compare */

		$this->assertStringStartsWith($expect['prefix'], $actual);
		$this->assertStringEndsWith($expect['suffix'], $actual);
	}

	/**
	 * testToken
	 *
	 * @param array $registry
	 * @param string $expect
	 *
	 * @dataProvider providerToken
	 *
	 * @since 2.6.0
	 */

	public function testToken($registry = array(), $expect = null)
	{
		/* setup */

		$this->_registry->init($registry);
		$form = new Html\Form($this->_registry, $this->_language);
		$form->init();
		$form->token();

		/* actual */

		$actual = $form;

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testButton
	 *
	 * @param string $method
	 * @param string $text
	 * @param array $attributeArray
	 * @param string $expect
	 *
	 * @dataProvider providerButton
	 *
	 * @since 2.6.0
	 */

	public function testButton($method = null, $text = null, $attributeArray = array(), $expect = null)
	{
		/* setup */

		$form = new Html\Form($this->_registry, $this->_language);
		$form->init();
		$form->$method($text, $attributeArray);

		/* actual */

		$actual = $form;

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
