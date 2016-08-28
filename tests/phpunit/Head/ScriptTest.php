<?php
namespace Redaxscript\Tests\Head;

use Redaxscript\Head;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * ScriptTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 * @author Balázs Szilágyi
 */
class ScriptTest extends TestCaseAbstract
{
	/**
	 * providerAppendRender
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerAppendRender()
	{
		return $this->getProvider('tests/provider/Head/script_append_render.json');
	}

	/**
	 * providerPrependRender
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerPrependRender()
	{
		return $this->getProvider('tests/provider/Head/script_prepend_render.json');
	}

	/**
	 * testRender
	 *
	 * @since 3.0.0
	 *
	 * @dataProvider providerAppendRender
	 *
	 * @param array $array
	 * @param string $single
	 * @param string $expected
	 */

	public function testAppendRender($array = [], $single = null, $expected = null)
	{
		/* setup */

		$scriptCore = Head\Script::getInstance();

		foreach ($array as $key => $value)
		{
			$scriptCore->append($value);
		}

		$scriptModule = Head\Script::getInstance();

		foreach ($single as $key => $value)
		{
			$scriptModule->append($key, $value);
		}

		/* actual */

		$actual = $scriptCore;

		/* compare */

		$this->assertEquals($expected, strval($actual));
	}

	/**
	 * testPrependRender
	 *
	 * @since 3.0.0
	 *
	 * @dataProvider providerPrependRender
	 *
	 * @param array $array
	 * @param string $single
	 * @param string $expected
	 */

	public function testPrependRender($array = [], $single = null, $expected = null)
	{
		/* setup */

		$scriptCore = Head\Script::getInstance();

		foreach ($array as $key => $value)
		{
			$scriptCore->prepend($value);
		}

		$scriptModule = Head\Script::getInstance();

		foreach ($single as $key => $value)
		{
			$scriptModule->prepend($key, $value);
		}

		/* actual */

		$actual = $scriptCore;

		/* compare */

		$this->assertEquals($expected, strval($actual));
	}
}
