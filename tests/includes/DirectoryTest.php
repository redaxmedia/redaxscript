<?php
use org\bovigo\vfs\vfsStream;

/**
 * Redaxscript Directory Test
 *
 * @since 2.1.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 * @author Gary Aylward
 */

class Redaxscript_Directory_Test extends PHPUnit_Framework_TestCase
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
		$contents = file_get_contents('tests/provider/directory_set_up.json');
		$output = json_decode($contents, true);
		$this->_root = vfsStream::setup('root', 0777, $output);
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
		$contents = file_get_contents('tests/provider/directory_get.json');
		$output = json_decode($contents, true);
		return $output;
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
		$contents = file_get_contents('tests/provider/directory_create.json');
		$output = json_decode($contents, true);
		return $output;
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
		$contents = file_get_contents('tests/provider/directory_remove.json');
		$output = json_decode($contents, true);
		return $output;
	}

	/**
	 * testGet
	 *
	 * @since 2.1.0
	 *
	 * @param array $path
	 * @param string|array $exclude
	 * @param array $expect
	 *
	 * @dataProvider providerGet
	 */

	public function testGet($path = null, $exclude = null, $expect = array())
	{
		/* setup */

		$directory = New Redaxscript_Directory(vfsStream::url($path), $exclude);

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

		$directory = New Redaxscript_Directory(vfsStream::url($path[1]));
		$directory->create($path[0], 511);

		/* result */

		$result = scandir(vfsStream::url($path[2]));

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

		$directory = New Redaxscript_Directory(vfsStream::url($path[1]));
		$directory->remove($path[0]);

		/* result */

		$result = scandir(vfsStream::url($path[2]));

		/* compare */

		$this->assertEquals($expect, $result);
	}
}
