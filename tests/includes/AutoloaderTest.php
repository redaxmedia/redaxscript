<?php

/**
 * Redaxscript Autoloader Test
 *
 * @since 2.1.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 * @author Sven Weingartner
 */
class Redaxscript_Autoloader_Test extends PHPUnit_Framework_TestCase
{

	/**
	 * providerFilePath
	 *
	 * @since 2.1.0
	 *
	 * @return array
	 */

	public function providerFilePath()
	{
		$contents = file_get_contents('tests/provider/autoloader_file_path.json');
		$output = json_decode($contents, true);
		return $output;
	}

	/**
	 * testDirectory
	 *
	 * @since 2.1.0
	 */

	public function testDirectory()
	{
		/* setup */

		$autoloader = new Redaxscript_Autoloader;
		$autoloader::init('includes');

		/* result */

		$result = $this->readAttribute($autoloader, '_directory');

		/* compare */

		$this->assertEquals('includes', $result);
	}

	/**
	 * testAutoloadFilePath
	 *
	 * @since 2.2.0
	 *
	 * @param string $className
	 * @param string $expect
	 *
	 * @dataProvider providerFilePath
	 */

	public function testAutoloadFilePath($className = null, $expect = null)
	{
		/* result */

		$result = class_exists($className);

		/* compare */

		$this->assertEquals($expect, $result, 'Classname: ' . $className);
	}

	/**
	 * testAutoloader
	 *
	 * @since 2.1.0
	 */

	public function testAutoloader()
	{
		/* result */

		$result = spl_autoload_functions();

		/* compare */

		$this->assertInternalType('array', $result);
	}
}

