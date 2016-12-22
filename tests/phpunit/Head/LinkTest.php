<?php
namespace Redaxscript\Tests\Head;

use Redaxscript\Head;
use Redaxscript\Tests\TestCaseAbstract;
use org\bovigo\vfs\vfsStream as Stream;

/**
 * LinkTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 * @author Balázs Szilágyi
 */

class LinkTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 3.0.0
	 */

	public function setUp()
	{
		Stream::setup('root', 0777, $this->getProvider('tests/provider/Head/link_set_up.json'));
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
		return $this->getProvider('tests/provider/Head/link_append.json');
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
		return $this->getProvider('tests/provider/Head/link_prepend.json');
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
		return $this->getProvider('tests/provider/Head/link_remove.json');
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
		return $this->getProvider('tests/provider/Head/link_concat.json');
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

		$link = Head\Link::getInstance();
		$link->init('append');
		foreach ($coreArray as $key => $value)
		{
			$link->append($value);
		}
		foreach ($moduleArray as $key => $value)
		{
			$link->appendFile($value);
		}

		/* actual */

		$actual = $link;

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

		$link = Head\Link::getInstance();
		$link->init('prepend');
		foreach ($coreArray as $value)
		{
			$link->prepend($value);
		}
		foreach ($moduleArray as $key => $value)
		{
			$link->prependFile($value);
		}

		/* actual */

		$actual = $link;

		/* compare */

		$this->assertEquals($this->normalizeEOL($expect), $actual);
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

		$link = Head\Link::getInstance();
		$link->init('remove');
		foreach ($coreArray as $key => $value)
		{
			$link->append($value);
		}
		$link->removeFile($deleteFile);

		/* actual */

		$actual = $link;

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
			'directory' => Stream::url('root/cache/styles')
		];
		$link = Head\Link::getInstance();
		$link->init('concat');
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

		$this->assertEquals($this->normalizeEOL($expect), $actual);
	}

	/**
	 * testRewrite
	 *
	 * @since 3.0.0
	 *
	 */

	public function testRewrite()
	{
		/* setup */

		$link = Head\Link::getInstance();
		$link
			->init('rewrite')
			->rewrite(
			[
				'test1' => 'test1',
				'test2' => 'test2'
			])
			->rewrite(
			[
				'test3' => 'test3'
			]);

		/* expect and actual */

		$expectArray =
		[
			'test1' => 'test1',
			'test2' => 'test2',
			'test3' => 'test3'
		];
		$actualArray = $this->getProperty($link, '_rewriteArray')['Redaxscript\Head\Link\Rewrite'];

		/* compare */

		$this->assertEquals($expectArray, $actualArray);
	}

	/**
	 * testInvalid
	 *
	 * @since 3.0.0
	 *
	 */

	public function testInvalid()
	{
		/* setup */

		$link = Head\Link::getInstance();
		$link->init('invalid');

		/* expect and actual */

		$actual = (string)$link;

		/* compare */

		$this->assertEquals('<!-- Redaxscript\Head\Link\Invalid -->', $actual);
	}
}
