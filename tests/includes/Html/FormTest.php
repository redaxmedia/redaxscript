<?php
namespace Redaxscript\Tests\Html;

use Redaxscript\Html;
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
	 * setUp
	 *
	 * @since 2.6.0
	 */

	protected function setUp()
	{
		$this->_registry = Registry::getInstance();
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
	 * testCreate
	 *
	 * @param array $registry
	 * @param array $options
	 * @param string $expect
	 *
	 * @dataProvider providerCreate
	 *
	 * @since 2.6.0
	 */

	public function testCreate($registry = array(), $options = array(), $expect = null)
	{
		/* setup */

		$this->_registry->init($registry);
		$form = new Html\Form($this->_registry);
		$form->init($options);

		/* actual */

		$actual = $form->render();

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
