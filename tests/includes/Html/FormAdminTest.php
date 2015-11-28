<?php
namespace Redaxscript\Tests\Html;

use Redaxscript\Html;
use Redaxscript\Language;
use Redaxscript\Registry;
use Redaxscript\Tests\TestCase;

/**
 * FormAdminTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class FormAdminTest extends TestCase
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
	 * @since 3.0.0
	 */

	protected function setUp()
	{
		$this->_registry = Registry::getInstance();
		$this->_language = Language::getInstance();
	}

	/**
	 * providerCreate
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerCreate()
	{
		return $this->getProvider('tests/provider/Html/form_admin_create.json');
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
	 * @since 3.0.0
	 */

	public function testCreate($attributeArray = array(), $options = array(), $expect = null)
	{
		/* setup */

		$form = new Html\FormAdmin($this->_registry, $this->_language);
		$form->init($attributeArray, $options);

		/* actual */

		$actual = $form;

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
