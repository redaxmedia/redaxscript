<?php
include_once('Config.php');

/**
 * Redaxscript Config Test
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Test
 * @author Henry Ruhs
 */

class Redaxscript_Config_Test extends PHPUnit_Framework_TestCase
{
	/**
	 * testSetAndGet
	 *
	 * @since 2.2.0
	 */

	public function testSetAndGet()
	{
		/* setup */

		Redaxscript_Config::set('host', 'localhost');

		/* result */

		$result = Redaxscript_Config::get('host');

		/* compare */

		$this->assertEquals('localhost', $result);
	}

	/**
	 * testGetAll
	 *
	 * @since 2.2.0
	 */

	public function testGetAll()
	{
		/* result */

		$result = Redaxscript_Config::get();

		/* compare */

		$this->assertArrayHasKey('host', $result);
	}
}
