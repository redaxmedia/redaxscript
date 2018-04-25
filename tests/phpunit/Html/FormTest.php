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
		$optionArray =
		[
			'adminName' => 'Test',
			'adminUser' => 'test',
			'adminPassword' => 'test',
			'adminEmail' => 'test@test.com'
		];
		$installer = $this->installerFactory();
		$installer->init();
		$installer->rawCreate();
		$installer->insertSettings($optionArray);
	}

	/**
	 * tearDown
	 *
	 * @since 3.1.0
	 */

	public function tearDown()
	{
		$this->dropDatabase();
	}

	/**
	 * providerCreate
	 *
	 * @since 2.6.0
	 *
	 * @return array
	 */

	public function providerCreate() : array
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

	public function providerLegend() : array
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

	public function providerLabel() : array
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

	public function providerInput() : array
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

	public function providerTextarea() : array
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

	public function providerSelect() : array
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

	public function providerSelectRange() : array
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

	public function providerCaptcha() : array
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

	public function providerToken() : array
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

	public function providerButton() : array
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

	public function providerLink() : array
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

	public function testCreate(array $attributeArray = [], array $optionArray = [], string $expect = null)
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

	public function testLegend(string $text = null, array $attributeArray = [], string $expect = null)
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

	public function testLabel(string $text = null, array $attributeArray = [], string $expect = null)
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

	public function testInput(string $method = null, array $attributeArray = [], string $expect = null)
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

	public function testTextarea(array $attributeArray = [], string $expect = null)
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
	 * @since 3.1.0
	 *
	 * @param array $optionArray
	 * @param array $selectArray
	 * @param array $attributeArray
	 * @param string $expect
	 *
	 * @dataProvider providerSelect
	 */

	public function testSelect(array $optionArray = [], array $selectArray = [], array $attributeArray = [], string $expect = null)
	{
		/* setup */

		$form = new Html\Form($this->_registry, $this->_language);
		$form->init();
		$form->select($optionArray, $selectArray, $attributeArray);

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
	 * @param array $selectArray
	 * @param array $attributeArray
	 * @param string $expect
	 *
	 * @dataProvider providerSelectRange

	 */

	public function testSelectRange(array $rangeArray = [], array $selectArray = [], array $attributeArray = [], string $expect = null)
	{
		/* setup */

		$form = new Html\Form($this->_registry, $this->_language);
		$form->init();
		$form->selectRange($rangeArray, $selectArray, $attributeArray);

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

	public function testCaptcha(array $expectArray = [])
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

	public function testToken(array $registryArray = [], string $expect = null)
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

	public function testButton(string $method = null, string $text = null, array $attributeArray = [], string $expect = null)
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

	public function testLink(string $method = null, string $text = null, array $attributeArray = [], string $expect = null)
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
