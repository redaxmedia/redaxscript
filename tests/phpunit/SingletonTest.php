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
 */

class SingletonTest extends TestCaseAbstract
{
	/**
	 * testReset
	 *
	 * @since 2.2.0
	 */

	public function testReset()
	{
		/* setup */

		$stub = $this->getMockBuilder('Redaxscript\Singleton')->disableOriginalConstructor()->getMockForAbstractClass();
		$stub->reset();

		/* actual */

		$actual = $stub->getInstance();

		/* compare */

		$this->assertInstanceOf('Redaxscript\Singleton', $actual);
	}
}
