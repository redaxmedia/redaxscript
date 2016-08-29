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
	 * testAppend
	 *
	 * @since 3.0.0
	 *
	 * @dataProvider providerAppend
	 *
	 * @param array $coreArray
	 * @param array $moduleArray
	 * @param string $expect
	 */

	public function testAppend($coreArray = [], $moduleArray = [], $expect = null)
	{
		/* setup */

		$scriptCore = Head\Script::getInstance();
		$scriptModule = Head\Script::getInstance();
		foreach ($coreArray as $key => $value)
		{
			$scriptCore->append($value);
		}
		foreach ($moduleArray as $key => $value)
		{
			$scriptModule->append($key, $value);
		}

		/* actual */

		$actual = $scriptCore->render();

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testPrepend
	 *
	 * @since 3.0.0
	 *
	 * @dataProvider providerPrepend
	 *
	 * @param array $coreArray
	 * @param array $moduleArray
	 * @param string $expect
	 */

	public function testPrepend($coreArray = [], $moduleArray = [], $expect = null)
	{
		/* setup */

		$scriptCore = Head\Script::getInstance();
		$scriptModule = Head\Script::getInstance();
		foreach ($coreArray as $key => $value)
		{
			$scriptCore->prepend($value);
		}
		foreach ($moduleArray as $key => $value)
		{
			$scriptModule->prepend($key, $value);
		}

		/* actual */

		$actual = $scriptCore->render();

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
