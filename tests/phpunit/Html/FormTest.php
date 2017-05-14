<?php
namespace Redaxscript\Tests\Html;

use Redaxscript\Html;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * FormTest
 *
 * @since 2.6.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class FormTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 3.1.0
	 */

	public function setUp()
	{
		parent::setUp();
		$installer = $this->installerFactory();
		$installer->init();
		$installer->rawCreate();
		$installer->insertSettings(
		[
			'adminName' => 'Test',
			'adminUser' => 'test',
			'adminPassword' => 'test',
			'adminEmail' => 'test@test.com'
		]);
	}

	/**
	 * tearDown
	 *
	 * @since 3.1.0
	 */

	public function tearDown()
	{
		$installer = $this->installerFactory();
		$installer->init();
		$installer->rawDrop();
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
	 * providerSelectRange
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerSelectRange()
	{
		return $this->getProvider('tests/provider/Html/form_select_range.json');
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
	 * providerLink
	 *
	 * @since 2.6.0
	 *
	 * @return array
	 */

	public function providerLink()
	{
		return $this->getProvider('tests/provider/Html/form_link.json');
	}

	/**
	 * testCreate
	 *
	 * @since 2.6.0
	 *
	 * @param array $attributeArray
	 * @param array $optionArray
	 * @param string $expect
	 *
	 * @dataProvider providerCreate
	 */

	public function testCreate($attributeArray = [], $optionArray = [], $expect = null)
	{
		/* setup */

		$form = new Html\Form($this->_registry, $this->_language);
		$form->init($attributeArray, $optionArray);

		/* actual */

		$actual = $form;

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testLegend
	 *
	 * @since 2.6.0
	 *
	 * @param string $text
	 * @param array $attributeArray
	 * @param string $expect
	 *
	 * @dataProvider providerLegend
	 */

	public function testLegend($text = null, $attributeArray = [], $expect = null)
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
	 * @since 2.6.0
	 *
	 * @param string $text
	 * @param array $attributeArray
	 * @param string $expect
	 *
	 * @dataProvider providerLabel
	 */

	public function testLabel($text = null, $attributeArray = [], $expect = null)
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
	 * @since 2.6.0
	 *
	 * @param string $method
	 * @param array $attributeArray
	 * @param string $expect
	 *
	 * @dataProvider providerInput
	 */

	public function testInput($method = null, $attributeArray = [], $expect = null)
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
	 * @since 2.6.0
	 *
	 * @param array $attributeArray
	 * @param string $expect
	 *
	 * @dataProvider providerTextarea
	 */

	public function testTextarea($attributeArray = [], $expect = null)
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
	 * @since 2.6.0
	 *
	 * @param array $optionArray
	 * @param array $attributeArray
	 * @param array $expect
	 *
	 * @dataProvider providerSelect
	 */

	public function testSelect($optionArray = [], $attributeArray = [], $expect = null)
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
	 * testSelectRange
	 *
	 * @since 3.0.0
	 *
	 * @param array $rangeArray
	 * @param array $attributeArray
	 * @param string $expect
	 *
	 * @dataProvider providerSelectRange

	 */

	public function testSelectRange($rangeArray = [], $attributeArray = [], $expect = null)
	{
		/* setup */

		$form = new Html\Form($this->_registry, $this->_language);
		$form->init();
		$form->selectRange($rangeArray, $attributeArray);

		/* actual */

		$actual = $form;

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testCaptcha
	 *
	 * @since 3.0.0
	 *
	 * @param array $expectArray
	 *
	 * @dataProvider providerCaptcha
	 */

	public function testCaptcha($expectArray = [])
	{
		/* setup */

		$form = new Html\Form($this->_registry, $this->_language);
		$form->init(null,
		[
			'captcha' => true
		]);
		$form->captcha($expectArray['type']);

		/* actual */

		$actual = $form->render();

		/* compare */

		$this->assertStringStartsWith($expectArray['start'], $actual);
		$this->assertStringEndsWith($expectArray['end'], $actual);
	}

	/**
	 * testToken
	 *
	 * @since 3.0.0
	 *
	 * @param array $registryArray
	 * @param string $expect
	 *
	 * @dataProvider providerToken
	 */

	public function testToken($registryArray = [], $expect = null)
	{
		/* setup */

		$this->_registry->init($registryArray);
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
	 * @since 2.6.0
	 *
	 * @param string $method
	 * @param string $text
	 * @param array $attributeArray
	 * @param string $expect
	 *
	 * @dataProvider providerButton
	 */

	public function testButton($method = null, $text = null, $attributeArray = [], $expect = null)
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

	/**
	 * testLink
	 *
	 * @since 3.0.0
	 *
	 * @param string $method
	 * @param string $text
	 * @param array $attributeArray
	 * @param string $expect
	 *
	 * @dataProvider providerLink
	 */

	public function testLink($method = null, $text = null, $attributeArray = [], $expect = null)
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
