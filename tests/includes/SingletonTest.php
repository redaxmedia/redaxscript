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

class SingletonTest extends TestCase
{
	/**
	 * instance of the stub class
	 *
	 * @var object
	 */

	protected $_stub;

	/**
	 * setUp
	 *
	 * @since 2.2.0
	 */

	protected function setUp()
	{
		$stub = $this->getMockBuilder('Redaxscript\Singleton')->disableOriginalConstructor()->getMockForAbstractClass();
		$this->_stub = $stub->getInstance();
	}

	/**
	 * testReset
	 *
	 * @since 2.2.0
	 */

	public function testReset()
	{
		/* setup */

		$this->_stub->reset();

		/* result */

		$result = $this->_stub->getInstance();

		/* compare */

		$this->assertInstanceOf('Redaxscript\Singleton', $result);
	}
}
