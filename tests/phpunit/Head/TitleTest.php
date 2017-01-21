<?php
namespace Redaxscript\Tests\Head;

use Redaxscript\Db;
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
	 * @param string $text
	 * @param string $expect
	 *
	 * @dataProvider providerRender
	 */

	public function testRender($text= null, $expect = null)
	{
		/* setup */

		$title = new Head\Title($this->_registry);

		/* actual */

		$actual = $title->render($text);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
