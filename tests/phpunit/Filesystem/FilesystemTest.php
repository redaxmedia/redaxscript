<?php
namespace Redaxscript\Tests\Filesystem;

use Redaxscript\Filesystem;
use org\bovigo\vfs\vfsStream as Stream;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * FilesystemTest
 *
 * @since 3.2.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
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
		Stream::setup('root', 0777, $this->getProvider('tests/provider/Filesystem/filesystem_setup.json'));
	}

	/**
	 * providerGetIterator
	 *
	 * @since 3.2.0
	 *
	 * @return array
	 */

	public function providerGetIterator() : array
	{
		return $this->getProvider('tests/provider/Filesystem/filesystem_get_iterator.json');
	}

	/**
	 * providerGetArray
	 *
	 * @since 3.2.0
	 *
	 * @return array
	 */

	public function providerGetArray() : array
	{
		return $this->getProvider('tests/provider/Filesystem/filesystem_get_array.json');
	}

	/**
	 * providerGetArray
	 *
	 * @since 3.2.0
	 *
	 * @return array
	 */

	public function providerGetSortArray() : array
	{
		return $this->getProvider('tests/provider/Filesystem/filesystem_get_sort_array.json');
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
	 * @dataProvider providerGetIterator
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
	 * @dataProvider providerGetArray
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
	 * testSortGetArray
	 *
	 * @since 3.2.0
	 *
	 * @param string $root
	 * @param bool $recursive
	 * @param array $filterArray
	 * @param array $expectArray
	 *
	 * @dataProvider providerGetSortArray
	 */

	public function testSortGetArray(string $root = null, bool $recursive = false, array $filterArray = [], array $expectArray = [])
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
