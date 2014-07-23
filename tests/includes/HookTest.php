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
	 * setUp
	 *
	 * @since 2.2.0
	 */

	protected function setUp()
	{
		Redaxscript_Hook::init();
	}

	/**
	 * testTrigger
	 *
	 * @since 2.2.0
	 */

	public function testTrigger()
	{
		/* result */

		$result = Redaxscript_Hook::trigger('test');

		/* compare */

		$this->assertEquals(false, $result);
	}
}