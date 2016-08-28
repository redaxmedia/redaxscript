<?php
namespace Redaxscript\Tests\Head;

use Redaxscript\Head;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * MetaTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 * @author Balázs Szilágyi
 */
class MetaTest extends TestCaseAbstract
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
		return $this->getProvider('tests/provider/Head/meta_render.json');
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

		$scriptCore = Head\Meta::getInstance();

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
