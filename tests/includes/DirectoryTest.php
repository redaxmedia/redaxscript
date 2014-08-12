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

class DirectoryTest extends TestCase
{
	/**
	 * root
	 *
	 * @var object
	 */

	private $_root;

	/**
	 * setUp
	 *
	 * @since 2.1.0
	 */

	protected function setUp()
	{
		$this->_root = Stream::setup('root', 0777, $this->getProvider('tests/provider/directory_set_up.json'));
	}

	/**
	 * providerGet
	 *
	 * @since 2.1.0
	 *
	 * @return array
	 */

	public function providerGet()
	{
		return $this->getProvider('tests/provider/directory_get.json');
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
	 * testGet
	 *
	 * @since 2.1.0
	 *
	 * @param array $path
	 * @param mixed $exclude
	 * @param array $expect
	 *
	 * @dataProvider providerGet
	 */

	public function testGet($path = null, $exclude = null, $expect = array())
	{
		/* setup */

		$directory = new Directory(Stream::url($path), $exclude);

		/* result */

		$result = $directory->get();

		/* compare */

		$this->assertEquals($expect, $result);
	}

	/**
	 * testCreate
	 *
	 * @since 2.1.0
	 *
	 * @param array $path
	 * @param array $expect
	 *
	 * @dataProvider providerCreate
	 */

	public function testCreate($path = array(), $expect = array())
	{
		/* setup */

		$directory = new Directory(Stream::url($path[1]));
		$directory->create($path[0], 511);

		/* result */

		$result = scandir(Stream::url($path[2]));

		/* compare */

		$this->assertEquals($expect, $result);
	}

	/**
	 * testRemove
	 *
	 * @since 2.1.0
	 *
	 * @param array $path
	 * @param array $expect
	 *
	 * @dataProvider providerRemove
	 */

	public function testRemove($path = array(), $expect = array())
	{
		/* setup */

		$directory = new Directory(Stream::url($path[1]));
		$directory->remove($path[0]);

		/* result */

		$result = scandir(Stream::url($path[2]));

		/* compare */

		$this->assertEquals($expect, $result);
	}
}
