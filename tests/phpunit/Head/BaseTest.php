<?php
namespace Redaxscript\Tests\Head;

use Redaxscript\Head;
use Redaxscript\Language;
use Redaxscript\Registry;
use Redaxscript\Request;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * BaseTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Balázs Szilágyi
 */

class BaseTest extends TestCaseAbstract
{
	/**
	 * instance of the registry class
	 *
	 * @var \Redaxscript\Registry
	 */

	protected $_registry;
	/**
	 * instance of the language class
	 *
	 * @var \Redaxscript\Language
	 */

	protected $_language;
	/**
	 * instance of the request class
	 *
	 * @var \Redaxscript\Request
	 */

	protected $_request;

	/**
	 * setUp
	 *
	 * @since 3.0.0
	 */

	public function setUp()
	{
		$this->_registry = Registry::getInstance();
		$this->_language = Language::getInstance();
		$this->_request = Request::getInstance();
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
		return $this->getProvider('tests/provider/Head/base_render.json');
	}

	/**
	 * testRender
	 *
	 * @since 3.0.0
	 *
	 * @param array $registryArray
	 * @param string $expect
	 *
	 * @dataProvider providerRender
	 */

	public function testRender($registryArray = [], $expect = null)
	{
		/* setup */

		$this->_registry->init($registryArray);
		$baseHead = new Head\Base($this->_registry);

		/* actual */

		$actual = $baseHead->render();

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
