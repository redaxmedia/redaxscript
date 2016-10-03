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
	 * providerInline
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerInline()
	{
		return $this->getProvider('tests/provider/Head/script_inline.json');
	}

	/**
	 * providerRemove
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerRemove()
	{
		return $this->getProvider('tests/provider/Head/script_remove.json');
	}

	/**
	 * providerTransportVar
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerTransportVar()
	{
		return $this->getProvider('tests/provider/Head/script_transport_var.json');
	}

	/**
	 * testAppend
	 *
	 * @since 3.0.0
	 *
	 * @param array $coreArray
	 * @param array $moduleArray
	 * @param string $expect
	 *
	 * @dataProvider providerAppend
	 */

	public function testAppend($coreArray = [], $moduleArray = [], $expect = null)
	{
		/* setup */

		$script = Head\Script::getInstance();
		$script->init('append');
		foreach ($coreArray as $key => $value)
		{
			$script->append($value);
		}
		foreach ($moduleArray as $key => $value)
		{
			$script->appendFile($value);
		}

		/* actual */

		$actual = $script->render();

		/* compare */

		$this->assertEquals($this->normalizeEOL($expect), $actual);
	}

	/**
	 * testPrepend
	 *
	 * @since 3.0.0
	 *
	 * @param array $coreArray
	 * @param array $moduleArray
	 * @param string $expect
	 *
	 * @dataProvider providerPrepend
	 */

	public function testPrepend($coreArray = [], $moduleArray = [], $expect = null)
	{
		/* setup */

		$script = Head\Script::getInstance();
		$script->init('prepend');
		foreach ($coreArray as $value)
		{
			$script->prepend($value);
		}
		foreach ($moduleArray as $key => $value)
		{
			$script->prependFile($value);
		}

		/* actual */

		$actual = $script->render();

		/* compare */

		$this->assertEquals($this->normalizeEOL($expect), $actual);
	}

	/**
	 * testInline
	 *
	 * @since 3.0.0
	 *
	 * @param array $coreArray
	 * @param array $moduleArray
	 * @param string $expect
	 *
	 * @dataProvider providerInline
	 */

	public function testInline($coreArray = [], $moduleArray = [], $expect = null)
	{
		/* setup */

		$script = Head\Script::getInstance();
		$script->init('inline');
		foreach ($coreArray as $value)
		{
			$script->appendInline($value);
		}
		foreach ($moduleArray as $value)
		{
			$script->prependInline($value);
		}

		/* actual */

		$actual = $script->render();

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testRemove
	 *
	 * @since 3.0.0
	 *
	 * @param array $coreArray
	 * @param string $deleteFile
	 * @param string $expect
	 *
	 * @dataProvider providerRemove
	 */

	public function testRemove($coreArray = [], $deleteFile = null, $expect = null)
	{
		/* setup */

		$script = Head\Script::getInstance();
		$script->init('remove');
		foreach ($coreArray as $key => $value)
		{
			$script->append($value);
		}
		$script->removeFile($deleteFile);

		/* actual */

		$actual = $script->render();

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testTransportVar
	 *
	 * @since 3.0.0
	 *
	 * @param array $transportArray
	 * @param string $expect
	 *
	 * @dataProvider providerTransportVar
	 */

	public function testTransportVar($transportArray = [], $expect = null)
	{
		/* setup */

		$script = Head\Script::getInstance();
		$script
			->init('transport')
			->transportVar($transportArray['key'], $transportArray['value']);

		/* actual */

		$actual = $script->render();

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
