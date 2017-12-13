<?php
namespace Redaxscript\Tests\Filesystem;

use Redaxscript\Filesystem;
use org\bovigo\vfs\vfsStream as Stream;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * FileTest
 *
 * @since 3.2.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class FileTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 3.2.0
	 */

	public function setUp()
	{
		Stream::setup('root', 0777, $this->getProvider('tests/provider/Filesystem/filesystem_setup.json'));
	}

	/**
	 * providerCreate
	 *
	 * @since 3.2.0
	 *
	 * @return array
	 */

	public function providerCreate() : array
	{
		return $this->getProvider('tests/provider/Filesystem/file_create.json');
	}

	/**
	 * providerWrite
	 *
	 * @since 3.2.0
	 *
	 * @return array
	 */

	public function providerReadAndWrite() : array
	{
		return $this->getProvider('tests/provider/Filesystem/file_read_and_write.json');
	}

	/**
	 * providerRemove
	 *
	 * @since 3.2.0
	 *
	 * @return array
	 */

	public function providerRemove() : array
	{
		return $this->getProvider('tests/provider/Filesystem/file_remove.json');
	}

	/**
	 * testCreate
	 *
	 * @since 3.2.0
	 *
	 * @param string $root
	 * @param bool $recursive
	 * @param string $file
	 * @param array $expectArray
	 *
	 * @dataProvider providerCreate
	 */

	public function testCreate(string $root = null, bool $recursive = null, string $file = null, array $expectArray = [])
	{
		/* setup */

		$filesystem = new Filesystem\File();
		$filesystem->init(Stream::url($root), $recursive);
		$filesystem->createFile($file);

		/* actual */

		$actualArray = $filesystem->getArray();

		/* compare */

		$this->assertEquals($expectArray, $actualArray);
	}

	/**
	 * testReadAndWrite
	 *
	 * @since 3.2.0
	 *
	 * @param string $root
	 * @param bool $recursive
	 * @param string $file
	 * @param string $content
	 * @param array $expectArray
	 *
	 * @dataProvider providerReadAndWrite
	 */

	public function testReadAndWrite(string $root = null, bool $recursive = null, string $file = null, string $content = null, array $expectArray = [])
	{
		/* setup */

		$filesystem = new Filesystem\File();
		$filesystem->init(Stream::url($root), $recursive);
		$filesystem->writeFile($file, $content);

		/* actual */

		$actualArray =
		[
			$filesystem->readFile($file),
			$filesystem->renderFile($file)
		];

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
	 * @param string $file
	 * @param array $expectArray
	 *
	 * @dataProvider providerRemove
	 */

	public function testRemove(string $root = null, bool $recursive = null, string $file = null, array $expectArray = [])
	{
		/* setup */

		$filesystem = new Filesystem\File();
		$filesystem->init(Stream::url($root), $recursive);
		$filesystem->removeFile($file);

		/* actual */

		$actualArray = $filesystem->getArray();

		/* compare */

		$this->assertEquals($expectArray, $actualArray);
	}
}
