<?php
namespace Redaxscript\Tests\Filesystem;

use org\bovigo\vfs\vfsStream as Stream;
use Redaxscript\Filesystem;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * DirectoryTest
 *
 * @since 3.2.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Filesystem\Directory
 */

class DirectoryTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 3.2.0
	 */

	public function setUp()
	{
		Stream::setup('root', 0777, $this->getJSON('tests' . DIRECTORY_SEPARATOR. 'provider' . DIRECTORY_SEPARATOR. 'Filesystem' . DIRECTORY_SEPARATOR. 'FilesystemTest_setUp.json'));
	}

	/**
	 * testCreate
	 *
	 * @since 3.2.0
	 *
	 * @param string $root
	 * @param bool $recursive
	 * @param string $directory
	 * @param array $expectArray
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testCreate(string $root = null, bool $recursive = null, string $directory = null, array $expectArray = [])
	{
		/* setup */

		$filesystem = new Filesystem\Directory();
		$filesystem->init(Stream::url($root), $recursive);
		$filesystem->createDirectory($directory);

		/* actual */

		$actualArray = $filesystem->getArray();

		/* compare */

		$this->assertEquals($expectArray, $actualArray);
	}

	/**
	 * testRemove
	 *
	 * @since 3.2.0
	 *
	 * @param string $root
	 * @param bool $recursive
	 * @param string $directory
	 * @param array $expectArray
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testRemove(string $root = null, bool $recursive = null, string $directory = null, array $expectArray = [])
	{
		/* setup */

		$filesystem = new Filesystem\Directory();
		$filesystem->init(Stream::url($root), $recursive);
		$filesystem->removeDirectory($directory);

		/* actual */

		$actualArray = $filesystem->getArray();

		/* compare */

		$this->assertEquals($expectArray, $actualArray);
	}

	/**
	 * testClear
	 *
	 * @since 3.2.0
	 *
	 * @param string $root
	 * @param bool $recursive
	 * @param array $expectArray
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testClear(string $root = null, bool $recursive = null, array $expectArray = [])
	{
		/* setup */

		$filesystem = new Filesystem\Directory();
		$filesystem->init(Stream::url($root), $recursive);
		$filesystem->clearDirectory();

		/* actual */

		$actualArray = $filesystem->getArray();

		/* compare */

		$this->assertEquals($expectArray, $actualArray);
	}
}
