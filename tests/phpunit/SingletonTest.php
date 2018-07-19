<?php
namespace Redaxscript\Tests;

/**
 * SingletonTest
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Singleton
 */

class SingletonTest extends TestCaseAbstract
{
	/**
	 * testGetInstance
	 *
	 * @since 3.0.0
	 */

	public function testGetInstance()
	{
		/* setup */

		$stub = $this->getMockBuilder('Redaxscript\Singleton')->disableOriginalConstructor()->getMockForAbstractClass();

		/* actual */

		$actual = $stub->getInstance();

		/* compare */

		$this->assertInstanceOf('Redaxscript\Singleton', $actual);
	}

	/**
	 * testClearInstance
	 *
	 * @since 3.0.0
	 */

	public function testClearInstance()
	{
		/* setup */

		$stub = $this->getMockBuilder('Redaxscript\Singleton')->disableOriginalConstructor()->getMockForAbstractClass();
		$stub->clearInstance();

		/* actual */

		$actual = $stub->getInstance();

		/* compare */

		$this->assertNull($actual);
	}
}
