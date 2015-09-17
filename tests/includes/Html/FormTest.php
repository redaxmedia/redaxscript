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
	 * @param array $options
	 * @param string $expect
	 *
	 * @dataProvider providerCreate
	 *
	 * @since 2.6.0
	 */

	public function testCreate($options = array(), $expect = null)
	{
		/* setup */

		$form = new Html\Form($this->_registry, $this->_language);
		$form->init($options);

		/* actual */

		$actual = $form;

		/* compare */

		$this->assertEquals($expect, $actual);
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
	 * @param string $expect
	 *
	 * @dataProvider providerButton
	 *
	 * @since 2.6.0
	 */

	public function testButton($method = null, $expect = null)
	{
		/* setup */

		$this->_registry->init();
		$form = new Html\Form($this->_registry, $this->_language);
		$form->init();
		$form->$method();

		/* actual */

		$actual = $form;

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
