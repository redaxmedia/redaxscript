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
	 * providerTestGet
	 *
	 * @since 2.1.0
	 *
	 * @return array
	 */

	public function providerTestGet()
	{
		$contents = file_get_contents('tests/provider/directory_get.json');
		$output = json_decode($contents, true);
		return $output;
	}

	/**
	 * providerTestCreate
	 *
	 * @since 2.1.0
	 *
	 * @return array
	 */

	public function providerTestCreate()
	{
		$contents = file_get_contents('tests/provider/directory_create.json');
		$output = json_decode($contents, true);
		return $output;
	}

	/**
	 * providerTestRemove
	 *
	 * @since 2.1.0
	 *
	 * @return array
	 */

	public function providerTestRemove()
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
	 * @dataProvider providerTestGet
	 */

	public function testGet($parameter = null, $ignore = null, $expect = array())
	{
		/* setup */

		$directory = New Redaxscript_Directory(vfsStream::url('root/languages'), $ignore);

		/* result */

		$result = $directory->get($parameter);

		/* compare */

		$this->assertEquals($expect, $result);
	}

	/**
	 * testCreate
	 *
	 * @since 2.1.0
	 *
	 * @dataProvider providerTestCreate
	 */

	public function testCreate($parameter = array(), $url = null, $scandir = null, $expectOne = array(), $expectTwo = array())
	{
		/* setup */

		$directory = New Redaxscript_Directory(vfsStream::url($url));

		/* result */

		$resultOne = $directory->create($parameter[0], $parameter[1]);
		$resultTwo = scandir(vfsStream::url($scandir));

		/* compare */

		$this->assertEquals($expectOne, $resultOne);
		$this->assertEquals($expectTwo, $resultTwo);
	}

	/**
	 * testRemove
	 *
	 * @since 2.1.0
	 *
	 * @dataProvider providerTestRemove
	 */

	public function testRemove($parameter = null, $url = null, $scandir = null, $expect = array())
	{
		/* setup */

		$directory = New Redaxscript_Directory(vfsStream::url($url));

		/* result */

		$directory->remove($parameter);
		$result = scandir(vfsStream::url($scandir));

		/* compare */

		$this->assertEquals($expect, $result);
	}

}
