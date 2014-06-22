<?php
include_once('config.php');

/**
 * Redaxscript Config Test
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class Redaxscript_Config_Test extends PHPUnit_Framework_TestCase
{
	/**
	 * testGet
	 *
	 * @since 2.2.0
	 */

	public function testGet()
	{
		/* result */

		$result = Redaxscript_Config::get('host');

		/* compare */

		$this->assertEquals('redaxscript.com', $result);
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
