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

class DirectoryTestAbstract extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 2.1.0
	 */

	protected function setUp()
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
	 * @param mixed $exclude
	 * @param array $expect
	 *
	 * @dataProvider providerGetArray
	 */

	public function testGetArray($path = null, $exclude = null, $expect = array())
	{
		/* setup */

		$directory = new Directory();
		$directory->init(Stream::url($path), $exclude);

		/* actual */

		$actual = $directory->getArray();

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testCreate
	 *
	 * @since 3.0.0
	 *
	 * @param string $path
	 * @param string $create
	 * @param array $expect
	 *
	 * @dataProvider providerCreate
	 */

	public function testCreate($path = null, $create = null, $expect = array())
	{
		/* setup */

		$directory = new Directory();
		$directory->init(Stream::url($path));
		$directory->create($create, 0777);

		/* actual */

		$actual = scandir(Stream::url($path));

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
	 * @param array $expect
	 *
	 * @dataProvider providerRemove
	 */

	public function testRemove($path = null, $remove = null, $expect = array())
	{
		/* setup */

		$directory = new Directory();
		$directory->init(Stream::url($path));
		$directory->remove($remove);

		/* actual */

		$actual = scandir(Stream::url($path));

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
