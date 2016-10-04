<?php
namespace Redaxscript\Tests;

use Redaxscript\Autoloader;

/**
 * AutoloaderTest
 *
 * @since 2.1.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 * @author Sven Weingartner
 */

class AutoloaderTest extends TestCaseAbstract
{
	/**
	 * providerFilePath
	 *
	 * @since 2.2.0
	 *
	 * @return array
	 */

	public function providerFilePath()
	{
		return $this->getProvider('tests/provider/autoloader_file_path.json');
	}

	/**
	 * testInit
	 *
	 * @since 3.0.0
	 */

	public function testInit()
	{
		/* setup */

		$autoloader = new Autoloader();
		$autoloader->init('test');

		/* actual */

		$actualArray = $this->readAttribute($autoloader, '_autoloadArray');

		/* compare */

		$this->assertEquals('test', $actualArray[1]);
	}

	/**
	 * testFilePath
	 *
	 * @since 2.2.0
	 *
	 * @param string $className
	 * @param string $expect
	 *
	 * @dataProvider providerFilePath
	 */

	public function testFilePath($className = null, $expect = null)
	{
		/* actual */

		$actual = class_exists($className);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}

