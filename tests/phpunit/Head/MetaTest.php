<?php
namespace Redaxscript\Tests\Controller;

use Redaxscript\Head;
use Redaxscript\Language;
use Redaxscript\Registry;
use Redaxscript\Request;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * CommentTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Balázs Szilágyi
 */

class MetaTest extends TestCaseAbstract
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
		return $this->getProvider('tests/provider/Head/meta_render.json');
	}

	/**
	 * testRender
	 *
	 * @since 3.0.0
	 *
	 * @dataProvider providerRender
	 *
	 * @param array $registryArray
	 * @param string $expect
	 */

	public function testRender($registryArray = [], $expect = null)
	{
		/* setup */

		$metaHead = new Head\Meta($this->_registry, $this->_language, $this->_request);
		foreach ($registryArray as $key => $value)
		{
			$metaHead->append($key, $value);
		}

		/* actual */

		$actual = $metaHead;

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
