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
	 * providerAppend
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerAppend()
	{
		return $this->getProvider('tests/provider/Head/script_append.json');
	}

	/**
	 * providerPrepend
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerPrepend()
	{
		return $this->getProvider('tests/provider/Head/script_prepend.json');
	}

	/**
	 * providerAppend
	 *
	 * @since 3.0.0
	 *
	 * @dataProvider providerAppend
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
	 * testPrepend
	 *
	 * @since 3.0.0
	 *
	 * @dataProvider providerPrepend
	 *
	 * @param array $array
	 * @param string $single
	 * @param string $expect
	 */

	public function testPrependRender($array = [], $single = null, $expect = null)
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

		$actual = $scriptCore->render();

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
