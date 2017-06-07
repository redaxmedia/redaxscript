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
		Stream::setup('root', 0777, $this->getProvider('tests/provider/Head/script_setup.json'));
	}

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
	 * providerConcat
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerConcat()
	{
		return $this->getProvider('tests/provider/Head/script_concat.json');
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
	 * @dataProvider providerPrepend
	 */

	public function testPrepend($coreArray = [], $moduleArray = [], $expect = null)
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
	 * @dataProvider providerInline
	 */

	public function testInline($coreArray = [], $moduleArray = [], $expect = null)
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
	 * @dataProvider providerRemove
	 */

	public function testRemove($coreArray = [], $deleteFile = null, $expect = null)
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
	 * @dataProvider providerConcat
	 */

	public function testConcat($concatArray = [], $expect = null)
	{
		/* setup */

		$optionArray =
		[
			'directory' => Stream::url('root/cache/scripts')
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
