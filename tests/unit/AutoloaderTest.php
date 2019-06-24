<?php
namespace Redaxscript\Tests;

use Redaxscript\Autoloader;
use function class_exists;

/**
 * AutoloaderTest
 *
 * @since 2.1.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 * @author Sven Weingartner
 *
 * @covers Redaxscript\Autoloader
 */

class AutoloaderTest extends TestCaseAbstract
{
	/**
	 * testInit
	 *
	 * @since 3.0.0
	 */

	public function testInit() : void
	{
		/* setup */

		$autoloader = new Autoloader();
		$autoloader->init('test');

		/* actual */

		$actualArray = $this->getProperty($autoloader, '_autoloadArray');

		/* compare */

		$this->assertContains('test', $actualArray);
	}

	/**
	 * testFilePath
	 *
	 * @since 2.2.0
	 *
	 * @param string $className
	 * @param bool $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testFilePath(string $className = null, bool $expect = null) : void
	{
		/* actual */

		$actual = class_exists($className);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}

