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

	public function providerRender()
	{
		return $this->getProvider('tests/provider/Head/link_render.json');
	}

	/**
	 * testRender
	 *
	 * @since 3.0.0
	 *
	 * @dataProvider providerRender
	 *
	 * @param array $array
	 * @param string $expected
	 */

	public function testAppendRender($array = [], $expected = null)
	{
		/* setup */

		$scriptCore = Head\Link::getInstance();

		foreach ($array as $key => $value)
		{
			$scriptCore->append($value);
		}

		/* actual */

		$actual = $scriptCore;

		/* compare */

		$this->assertEquals($expected, strval($actual));
	}
}
