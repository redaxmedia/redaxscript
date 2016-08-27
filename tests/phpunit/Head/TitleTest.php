<?php
namespace Redaxscript\Tests\Head;

use Redaxscript\Head;
use Redaxscript\Registry;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * TitleTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Balázs Szilágyi
 */

class TitleTest extends TestCaseAbstract
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
	 * @since 3.0.0
	 */

	public function setUp()
	{
		$this->_registry = Registry::getInstance();
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
		return $this->getProvider('tests/provider/Head/title_render.json');
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
		$titleHead = new Head\Title($this->_registry);

		/* actual */

		$actual = $titleHead->render();

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
