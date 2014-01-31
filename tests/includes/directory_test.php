<?php

/**
 * directory test
 *
 * @since 2.1.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Gary Aylward
 */

/* Use vfsStream namespace */
use org\bovigo\vfs\vfsStream;

/**
 * Redaxscript_Directory_Test
 *
 * @since 2.1.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Gary Aylward
 */

class Redaxscript_Directory_Test extends PHPUnit_Framework_TestCase
{

	/**
	 *
	 * @var vfsStreamDirectory
	 */

	private $root;

	/**
	 * setUp
	 *
	 * gets an instance of the directory class to be used in each test
	 * sets up vfsStream mock for filesystem
	 *
	 * @since 2.1.0
	 */

	protected function setUp()
	{
		$structure = array(
			'languages' => array(
				'.' => '',
				'..' => '',
				'en.php' => 'English',
				'de.php' => 'German',
				'misc.php' => 'Miscellaneous',
			),
			'folder1' => array(
				'folder2' => array(
					'file2a.txt' => 'content file2a',
					'file2b.txt' => 'content file2b',
				),
				'file1a.txt' => 'content file1a',
				'file1b.txt' => 'content file1b',
			),
			'file.txt' => 'content',
		);
		$this->root = vfsStream::setup('root', 0777, $structure);
	}

	/**
	 * providerTestGetOutput
	 *
	 * data provider for the testGetOutput() method
	 *
	 * @since 2.1.0
	 *
	 * @return array
	 */

	public function providerTestGetOutput()
	{
		return array(
			/* Test 0 - get all files in a directory */
			array(
				/* input */
				'',
				/* exclude */
				'',
				/* expected result */
				array(
					'de.php',
					'en.php',
					'misc.php'
				),
			),
			/* Test 1 - get all files with exclusion */
			array(
				/* input */
				'',
				/* exclude */
				'misc.php',
				/* expected result */
				array(
					'de.php',
					'en.php'
				),
			),
			/* Test 2 - get all files with a different exclusion */
			array(
				/* input */
				'',
				/* exclude */
				'de.php',
				/* expected result */
				array(
					'en.php',
					'misc.php'
				),
			),
			/* Test 3 - get an individual file */
			array(
				/* input */
				0,
				/* exclude */
				'',
				/* expected result */
				'de.php'
			),
		);
	}

	/**
	 * testGetOutput
	 *
	 * Test for the getOutput method
	 *
	 * @since 2.1.0
	 *
	 * @dataProvider providerTestGetOutput
	 */

	public function testGetOutput($input, $exclude, $expectedResult)
	{
		$directory = New Redaxscript_Directory(vfsStream::url('root/languages'), $exclude);
		$result = $directory->getOutput($input);
		/* test result */
		$this->assertEquals($expectedResult, $result);
	}

	/**
	 * providerTestRemove
	 *
	 * data provider for the testRemove() method
	 *
	 * @since 2.1.0
	 *
	 * @return array
	 */

	public function providerTestRemove()
	{
		return array(
			/* Test 0 - remove multiple directory levels recursively */
			array(
				/* input */
				'folder1',
				/* base */
				'root',
				/* scan dir */
				'root',
				/* expected output */
				array(
					'file.txt',
					'languages',
				),
			),
			/* Test 1 - remove a single sub-directory */
			array(
				/* input */
				'folder1/folder2',
				/* base */
				'root',
				/* scan dir */
				'root/folder1',
				/* expected output */
				array(
					'file1a.txt',
					'file1b.txt',
				),
			),
			/* Test 2 - remove everything at the current level */
			array(
				/* input */
				'',
				/* base */
				'root/folder1',
				/* scan dir */
				'root',
				/* expected output */
				array(
					'file.txt',
					'languages',
				),
			),
			/* Test 3 - remove a single file */
			array(
				/* input */
				'file.txt',
				/* base */
				'root',
				/* scan dir */
				'root',
				/* expected output */
				array(
					'folder1',
					'languages',
				),
			),
		);
	}

	/**
	 * testRemove
	 *
	 * Test for the remove method
	 *
	 * @since 2.1.0
	 *
	 * @dataProvider providerTestRemove
	 */

	public function testRemove($input, $base, $scandir, $expectedResult)
	{
		$directory = New Redaxscript_Directory(vfsStream::url($base), '');
		$directory->remove($input);
		/* check that the file/folder has been removed */
		$this->assertEquals($expectedResult, scandir(vfsStream::url($scandir)));
	}

	/**
	 * providerTestCreate
	 *
	 * Data provider for the create test method
	 *
	 * @since 2.1.0
	 *
	 * @return array
	 */

	public function providerTestCreate()
	{
		return array(
			/* Test 0 - successfully create a directory */
			array(
				/* input */
				array('test_folder', 0777),
				/* base */
				'root',
				/* scandir */
				'root',
				/* return value */
				true,
				/* expected result */
				array(
					'file.txt',
					'folder1',
					'languages',
					'test_folder'
				),
			),
			/* Test 1 - invalid directory name */
			array(
				/* input */
				array('test_folder*', 0777),
				/* base */
				'root',
				/* scandir */
				'root',
				/* return value */
				false,
				/* expected result */
				array(
					'file.txt',
					'folder1',
					'languages'
				),
			),
			/* Test 2 - invalid directory name */
			array(
				/* input */
				array('test/folder', 0777),
				/* base */
				'root',
				/* scandir */
				'root',
				/* return value */
				false,
				/* expected result */
				array(
					'file.txt',
					'folder1',
					'languages'
				),
			),
			/* Test 3 - directory already exists */
			array(
				/* input */
				array('folder1', 0777),
				/* base */
				'root',
				/* scandir */
				'root',
				/* return value */
				false,
				/* expected result */
				array(
					'file.txt',
					'folder1',
					'languages'
				),
			),
		);
	}

	/**
	 * testCreate
	 *
	 * Test for the create method
	 *
	 * @since 2.1.0
	 *
	 * @dataProvider providerTestCreate
	 */

	public function testCreate($input, $base, $scandir, $return, $expectedResult)
	{
		$directory = New Redaxscript_Directory(vfsStream::url($base), '');
		/* check that the file has been created */
		$returnValue = $directory->create($input[0], $input[1]);
		$this->assertEquals($return, $returnValue);
		/* check the new directory listing */
		$this->assertEquals($expectedResult, scandir(vfsStream::url($scandir)));
	}
}
