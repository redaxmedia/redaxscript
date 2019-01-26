<?php
namespace Redaxscript\Tests\Filesystem;

use org\bovigo\vfs\vfsStream as Stream;
use Redaxscript\Filesystem;
use Redaxscript\Tests\TestCaseAbstract;
use function iterator_count;

/**
 * FilesystemTest
 *
 * @since 3.2.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Filesystem\Filesystem
 */

class FilesystemTest extends TestCaseAbstract
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
	 * testGetIterator
	 *
	 * @since 3.2.0
	 *
	 * @param string $root
	 * @param bool $recursive
	 * @param array $filterArray
	 * @param array $expectArray
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testGetIterator(string $root = null, bool $recursive = false, array $filterArray = [], array $expectArray = [])
	{
		/* setup */

		$filesystem = new Filesystem\Filesystem();
		$filesystem->init(Stream::url($root), $recursive, $filterArray);

		/* actual */

		$actualIterator = $filesystem->getIterator();

		/* process iterator */

		if (iterator_count($actualIterator))
		{
			foreach ($actualIterator as $key => $item)
			{
				$file = $item->getFileName();
				$path = $item->getPathName();
				$this->assertEquals($expectArray[$file], $this->normalizeSeparator($path));
			}
		}
		else
		{
			$this->assertObject($actualIterator);
		}
	}

	/**
	 * testGetArray
	 *
	 * @since 3.2.0
	 *
	 * @param string $root
	 * @param bool $recursive
	 * @param array $filterArray
	 * @param array $expectArray
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testGetArray(string $root = null, bool $recursive = false, array $filterArray = [], array $expectArray = [])
	{
		/* setup */

		$filesystem = new Filesystem\Filesystem();
		$filesystem->init(Stream::url($root), $recursive, $filterArray);

		/* actual */

		$actualArray = $filesystem->getArray();

		/* compare */

		$this->assertEquals($expectArray, $actualArray);
	}

	/**
	 * testGetSortArray
	 *
	 * @since 3.2.0
	 *
	 * @param string $root
	 * @param bool $recursive
	 * @param array $filterArray
	 * @param array $expectArray
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testGetSortArray(string $root = null, bool $recursive = false, array $filterArray = [], array $expectArray = [])
	{
		/* setup */

		$filesystem = new Filesystem\Filesystem();
		$filesystem->init(Stream::url($root), $recursive, $filterArray);

		/* actual */

		$actualArray = $filesystem->getSortArray();

		/* compare */

		$this->assertEquals($expectArray, $actualArray);
	}
}
