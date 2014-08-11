<?php
namespace Redaxscript\Tests;
use Redaxscript\Config;

/**
 * ConfigTest
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class ConfigTest extends TestCase
{
	/**
	 * testSetAndGet
	 *
	 * @since 2.2.0
	 */

	public function testSetAndGet()
	{
		/* setup */

		Config::set('host', 'localhost');

		/* result */

		$result = Config::get('host');

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

		$result = Config::get();

		/* compare */

		$this->assertArrayHasKey('host', $result);
	}
}
