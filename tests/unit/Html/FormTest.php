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
 *
 * @covers Redaxscript\Html\Form
 */

class FormTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 3.1.0
	 */

	public function setUp() : void
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

	public function tearDown() : void
	{
		$this->dropDatabase();
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
	 * @dataProvider providerAutoloader
	 */

	public function testCreate(array $attributeArray = [], array $optionArray = [], string $expect = null) : void
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
	 * @dataProvider providerAutoloader
	 */

	public function testLegend(string $text = null, array $attributeArray = [], string $expect = null) : void
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
	 * @dataProvider providerAutoloader
	 */

	public function testLabel(string $text = null, array $attributeArray = [], string $expect = null) : void
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
	 * @dataProvider providerAutoloader
	 */

	public function testInput(string $method = null, array $attributeArray = [], string $expect = null) : void
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
	 * @dataProvider providerAutoloader
	 */

	public function testTextarea(array $attributeArray = [], string $expect = null) : void
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
	 * @dataProvider providerAutoloader
	 */

	public function testSelect(array $optionArray = [], array $selectArray = [], array $attributeArray = [], string $expect = null) : void
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
	 * @dataProvider providerAutoloader

	 */

	public function testSelectRange(array $rangeArray = [], array $selectArray = [], array $attributeArray = [], string $expect = null) : void
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
	 * @dataProvider providerAutoloader
	 */

	public function testCaptcha(array $expectArray = []) : void
	{
		/* setup */

		$form = new Html\Form($this->_registry, $this->_language);
		$form->init([],
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
	 * @dataProvider providerAutoloader
	 */

	public function testToken(array $registryArray = [], string $expect = null) : void
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
	 * @dataProvider providerAutoloader
	 */

	public function testButton(string $method = null, string $text = null, array $attributeArray = [], string $expect = null) : void
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
	 * @dataProvider providerAutoloader
	 */

	public function testLink(string $method = null, string $text = null, array $attributeArray = [], string $expect = null) : void
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
