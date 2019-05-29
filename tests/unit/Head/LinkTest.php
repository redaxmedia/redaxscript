<?php
namespace Redaxscript\Tests\Head;

use org\bovigo\vfs\vfsStream as Stream;
use Redaxscript\Head;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * LinkTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 * @author Balázs Szilágyi
 *
 * @covers Redaxscript\Head\HeadAbstract
 * @covers Redaxscript\Head\Link
 */

class LinkTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 3.0.0
	 */

	public function setUp() : void
	{
		Stream::setup('root', 0777, $this->getJSON('tests' . DIRECTORY_SEPARATOR. 'unit-provider' . DIRECTORY_SEPARATOR. 'Head' . DIRECTORY_SEPARATOR. 'LinkTest_setUp.json'));
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

	public function testAppend(array $coreArray = [], array $moduleArray = [], string $expect = null) : void
	{
		/* setup */

		$link = Head\Link::getInstance();
		$link->init('append');

		/* process core */

		foreach ($coreArray as $key => $value)
		{
			$link->append($value);
		}

		/* process module */

		foreach ($moduleArray as $key => $value)
		{
			$link->appendFile($value);
		}

		/* actual */

		$actual = $link;

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

	public function testPrepend(array $coreArray = [], array $moduleArray = [], string $expect = null) : void
	{
		/* setup */

		$link = Head\Link::getInstance();
		$link->init('prepend');

		/* process core */

		foreach ($coreArray as $value)
		{
			$link->prepend($value);
		}

		/* process module */

		foreach ($moduleArray as $key => $value)
		{
			$link->prependFile($value);
		}

		/* actual */

		$actual = $link;

		/* compare */

		$this->assertEquals($expect, $this->normalizeNewline($actual));
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

	public function testRemove(array $coreArray = [], string $deleteFile = null, string $expect = null) : void
	{
		/* setup */

		$link = Head\Link::getInstance();
		$link->init('remove');

		/* process core */

		foreach ($coreArray as $key => $value)
		{
			$link->append($value);
		}
		$link->removeFile($deleteFile);

		/* actual */

		$actual = $link;

		/* compare */

		$this->assertEquals($expect, $this->normalizeNewline($actual));
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

	public function testConcat(array $concatArray = [], string $expect = null) : void
	{
		/* setup */

		$optionArray =
		[
			'directory' => Stream::url('root' . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'styles')
		];
		$link = Head\Link::getInstance();
		$link->init('concat');

		/* process concat */

		foreach ($concatArray as $key => $value)
		{
			$link->append($value);
		}
		$link
			->concat($optionArray)
			->concat($optionArray);

		/* actual */

		$actual = $link;

		/* compare */

		$this->assertEquals($expect, $this->normalizeNewline($actual));
	}

	/**
	 * testRewrite
	 *
	 * @since 3.0.0
	 */

	public function testRewrite() : void
	{
		/* setup */

		$link = Head\Link::getInstance();
		$link
			->init('rewrite')
			->rewrite(
			[
				'rewrite-one' => 'rewrite-one',
				'rewrite-two' => 'rewrite-two'
			])
			->rewrite(
			[
				'rewrite-three' => 'rewrite-three'
			]);

		/* expect and actual */

		$expectArray =
		[
			'rewrite-one' => 'rewrite-one',
			'rewrite-two' => 'rewrite-two',
			'rewrite-three' => 'rewrite-three'
		];
		$actualArray = $this->getProperty($link, '_rewriteArray')['Redaxscript\Head\Link\Rewrite'];

		/* compare */

		$this->assertEquals($expectArray, $actualArray);
	}

	/**
	 * testInvalid
	 *
	 * @since 3.0.0
	 */

	public function testInvalid() : void
	{
		/* setup */

		$link = Head\Link::getInstance();
		$link->init('invalid');

		/* actual */

		$actual = $link;

		/* compare */

		$this->assertEquals('<!-- Redaxscript\Head\Link\Invalid -->', $actual);
	}
}
