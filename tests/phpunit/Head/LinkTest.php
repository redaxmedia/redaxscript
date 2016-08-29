<?php
namespace Redaxscript\Tests\Head;

use Redaxscript\Head;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * LinkTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Balázs Szilágyi
 */

class LinkTest extends TestCaseAbstract
{
	/**
	 * providerRender
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerAppend()
	{
		return $this->getProvider('tests/provider/Head/link_append.json');
	}

	/**
	 * testAppend
	 *
	 * @since 3.0.0
	 *
	 * @dataProvider providerAppend
	 *
	 * @param array $array
	 * @param string $expect
	 */

	public function testAppend($array = [], $expect = null)
	{
		/* setup */

		$linkCore = Head\Link::getInstance();

		foreach ($array as $key => $value)
		{
			$linkCore->append($value);
		}

		/* actual */

		$actual = $linkCore->render();

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
