<?php

/**
 * Redaxscript Hook Test
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class Redaxscript_Hook_Test extends PHPUnit_Framework_TestCase
{
	/**
	 * instance of the registry class
	 *
	 * @var object
	 */

	protected $_registry;

	/**
	 * setUp
	 *
	 * @since 2.2.0
	 */

	public function setUp()
	{
		$this->_registry = Redaxscript_Registry::getInstance();
	}

	/**
	 * testTrigger
	 *
	 * @since 2.2.0
	 */

	public function testTrigger()
	{
		/* setup */

		Redaxscript_Hook::init($this->_registry);

		/* result */

		$result = Redaxscript_Hook::trigger('test');

		/* compare */

		$this->assertEquals(false, $result);
	}
}