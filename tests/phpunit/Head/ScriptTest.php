<?php
namespace Redaxscript\Tests\Head;

use Redaxscript\Head;
use Redaxscript\Tests\TestCaseAbstract;
use org\bovigo\vfs\vfsStream as Stream;

/**
 * ScriptTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 * @author Balázs Szilágyi
 *
 * @covers Redaxscript\Head\HeadAbstract
 * @covers Redaxscript\Head\Script
 */

class ScriptTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 3.0.0
	 */

	public function setUp()
	{
		Stream::setup('root', 0777, $this->getJSON('tests' . DIRECTORY_SEPARATOR. 'provider' . DIRECTORY_SEPARATOR. 'Head' . DIRECTORY_SEPARATOR. 'ScriptTest_setUp.json'));
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
	 * @dataProvider providerAutoloader
	 */

	public function testAppend(array $coreArray = [], array $moduleArray = [], string $expect = null)
	{
		/* setup */

		$script = Head\Script::getInstance();
		$script->init('append');

		/* process core */

		foreach ($coreArray as $key => $value)
		{
			$script->append($value);
		}

		/* process module */

		foreach ($moduleArray as $key => $value)
		{
			$script->appendFile($value);
		}

		/* actual */

		$actual = $script;

		/* compare */

		$this->assertEquals($expect, $this->normalizeNewline($actual));
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
	 * @dataProvider providerAutoloader
	 */

	public function testPrepend(array $coreArray = [], array $moduleArray = [], string $expect = null)
	{
		/* setup */

		$script = Head\Script::getInstance();
		$script->init('prepend');

		/* process core */

		foreach ($coreArray as $value)
		{
			$script->prepend($value);
		}

		/* process module */

		foreach ($moduleArray as $key => $value)
		{
			$script->prependFile($value);
		}

		/* actual */

		$actual = $script;

		/* compare */

		$this->assertEquals($expect, $this->normalizeNewline($actual));
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
	 * @dataProvider providerAutoloader
	 */

	public function testInline(array $coreArray = [], array $moduleArray = [], string $expect = null)
	{
		/* setup */

		$script = Head\Script::getInstance();
		$script->init('inline');

		/* process core */

		foreach ($coreArray as $value)
		{
			$script->appendInline($value);
		}

		/* process module */

		foreach ($moduleArray as $value)
		{
			$script->prependInline($value);
		}

		/* actual */

		$actual = $script;

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
	 * @dataProvider providerAutoloader
	 */

	public function testRemove(array $coreArray = [], string $deleteFile = null, string $expect = null)
	{
		/* setup */

		$script = Head\Script::getInstance();
		$script->init('remove');

		/* process core */

		foreach ($coreArray as $key => $value)
		{
			$script->append($value);
		}
		$script->removeFile($deleteFile);

		/* actual */

		$actual = $script;

		/* compare */

		$this->assertEquals($expect, $this->normalizeNewline($actual));
	}

	/**
	 * testTransportVar
	 *
	 * @since 3.0.0
	 *
	 * @param array $transportArray
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testTransportVar(array $transportArray = [], string $expect = null)
	{
		/* setup */

		$script = Head\Script::getInstance();
		$script
			->init('transport')
			->transportVar($transportArray['key'], $transportArray['value']);

		/* actual */

		$actual = $script;

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testConcat
	 *
	 * @since 3.0.0
	 *
	 * @param array $concatArray
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testConcat(array $concatArray = [], string $expect = null)
	{
		/* setup */

		$optionArray =
		[
			'directory' => Stream::url('root' . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'scripts')
		];
		$script = Head\Script::getInstance();
		$script->init('concat');

		/* process concat */

		foreach ($concatArray as $key => $value)
		{
			$script->append($value);
		}
		$script
			->concat($optionArray)
			->concat($optionArray);

		/* actual */

		$actual = $script;

		/* compare */

		$this->assertEquals($expect, $this->normalizeNewline($actual));
	}

	/**
	 * testInvalid
	 *
	 * @since 3.0.0
	 */

	public function testInvalid()
	{
		/* setup */

		$script = Head\Script::getInstance();
		$script->init('invalid');

		/* actual */

		$actual = $script;

		/* compare */

		$this->assertEquals('<!-- Redaxscript\Head\Script\Invalid -->', $actual);
	}
}
