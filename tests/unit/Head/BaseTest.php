<?php
namespace Redaxscript\Tests\Head;

use Redaxscript\Head;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * BaseTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Balázs Szilágyi
 *
 * @covers Redaxscript\Head\Base
 * @covers Redaxscript\Head\HeadAbstract
 */

class BaseTest extends TestCaseAbstract
{
	/**
	 * testRender
	 *
	 * @since 3.0.0
	 *
	 * @param array $registryArray
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testRender(array $registryArray = [], string $expect = null) : void
	{
		/* setup */

		$this->_registry->init($registryArray);
		$base = new Head\Base($this->_registry);

		/* actual */

		$actual = $base;

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
