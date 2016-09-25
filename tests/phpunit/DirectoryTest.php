<?php
namespace Redaxscript\Tests;

use Redaxscript\Directory;
use org\bovigo\vfs\vfsStream as Stream;

/**
 * DirectoryTest
 *
 * @since 2.1.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 * @author Gary Aylward
 */

class DirectoryTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 2.1.0
	 */

	public function setUp()
	{
		Stream::setup('root', 0777, $this->getProvider('tests/provider/directory_set_up.json'));
	}

	/**
	 * providerGetArray
	 *
	 * @since 2.1.0
	 *
	 * @return array
	 */

	public function providerGetArray()
	{
		return $this->getProvider('tests/provider/directory_get_array.json');
	}

	/**
	 * providerCreate
	 *
	 * @since 2.1.0
	 *
	 * @return array
	 */

	public function providerCreate()
	{
		return $this->getProvider('tests/provider/directory_create.json');
	}

	/**
	 * providerPut
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerPut()
	{
		return $this->getProvider('tests/provider/directory_put.json');
	}

	/**
	 * providerRemove
	 *
	 * @since 2.1.0
	 *
	 * @return array
	 */

	public function providerRemove()
	{
		return $this->getProvider('tests/provider/directory_remove.json');
	}

	/**
	 * testGetArray
	 *
	 * @since 2.1.0
	 *
	 * @param string $path
	 * @param array $excludeArray
	 * @param array $expectArray
	 *
	 * @dataProvider providerGetArray
	 */

	public function testGetArray($path = null, $excludeArray = [], $expectArray = [])
	{
		/* setup */

		$directory = new Directory();
		$directory->init(Stream::url($path), $excludeArray);

		/* actual */

		$actualArray = $directory->getArray();

		/* compare */

		$this->assertEquals($expectArray, $actualArray);
	}

	/**
	 * testCreate
	 *
	 * @since 3.0.0
	 *
	 * @param string $path
	 * @param string $create
	 * @param array $expectArray
	 *
	 * @dataProvider providerCreate
	 */

	public function testCreate($path = null, $create = null, $expectArray = [])
	{
		/* setup */

		$directory = new Directory();
		$directory->init(Stream::url($path));
		$directory->create($create, 0777);

		/* actual */

		$actualArray = scandir(Stream::url($path));

		/* compare */

		$this->assertEquals($expectArray, $actualArray);
	}

	/**
	 * testPut
	 *
	 * @since 3.0.0
	 *
	 * @param string $path
	 * @param array $putArray
	 * @param string $expect
	 *
	 * @dataProvider providerPut
	 */

	public function testPut($path = null, $putArray = [], $expect = null)
	{
		/* setup */

		$directory = new Directory();
		$directory->init(Stream::url($path));
		$directory->put($putArray[0], $putArray[1]);

		/* actual */

		$actual = file_get_contents(Stream::url($path . '/' . $putArray[0]));

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testRemove
	 *
	 * @since 3.0.0
	 *
	 * @param string $path
	 * @param string $remove
	 * @param array $expectArray
	 *
	 * @dataProvider providerRemove
	 */

	public function testRemove($path = null, $remove = null, $expectArray = [])
	{
		/* setup */

		$directory = new Directory();
		$directory->init(Stream::url($path));
		$directory->remove($remove);

		/* actual */

		$actualArray = scandir(Stream::url($path));

		/* compare */

		$this->assertEquals($expectArray, $actualArray);
	}

	/**
	 * testClear
	 *
	 * @since 3.0.0
	 */

	public function testClear()
	{
		/* setup */

		$directory = new Directory();
		$directory->init(Stream::url('root'));
		$directory->clear();

		/* actual */

		$actualArray = scandir(Stream::url('root'));

		/* compare */

		$this->assertFalse($actualArray);
	}
}
