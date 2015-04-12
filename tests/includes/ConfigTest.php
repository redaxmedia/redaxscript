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

		/* actual */

		$actual = Config::get('host');

		/* compare */

		$this->assertEquals('localhost', $actual);
	}

	/**
	 * testGetAll
	 *
	 * @since 2.2.0
	 */

	public function testGetAll()
	{
		/* actual */

		$actual = Config::get();

		/* compare */

		$this->assertArrayHasKey('host', $actual);
	}
}
