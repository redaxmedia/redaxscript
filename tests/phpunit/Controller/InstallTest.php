<?php
namespace Redaxscript\Tests\Controller;

use Redaxscript\Language;
use Redaxscript\Registry;
use Redaxscript\Tests\TestCaseAbstract;
use Redaxscript\View;

/**
 * InstallControllerTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 *
 * @author
 */

class InstallTest extends TestCaseAbstract
{
	/**
	 * instance of the registry class
	 *
	 * @var Registry
	 */

	protected $_registry;
	/**
	 * instance of the language class
	 *
	 * @var Language
	 */

	protected $_language;

	/**
	 * setUp
	 *
	 * @since 3.0.0
	 */

	public function setUp()
	{
		$this->_registry = Registry::getInstance();
		$this->_language = Language::getInstance();
	}

	/**
	 * tearDownAfterClass
	 *
	 * @since 3.0.0
	 */

	public static function tearDownAfterClass()
	{

	}

	/**
	 * providerRender
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerRender()
	{
		return $this->getProvider('tests/provider/Controller/install_process.json');
	}

	/**
	 * testRender
	 *
	 * @since 3.0.0
	 *
	 * @param array $optionArray
	 * @param array $hashArray
	 * @param string $expect
	 *
	 * @dataProvider providerRender
	 */

	public function testRender($optionArray = array(), $hashArray = array(), $expect = null)
	{
		// TODO: Henry / Balázs: create unit tests
	}
}
