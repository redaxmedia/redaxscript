<?php
namespace Redaxscript\Tests;

use Redaxscript\Installer;

/**
 * InstallerTest
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class InstallerTest extends TestCase
{
	/**
	 * testCreateMysql
	 *
	 * @since 2.4.0
	 */

	public function testCreateMysql()
	{
		/* setup */

		$installer = new Installer();

		/* actual */

		$actual = $installer->createMysql();

		/* compare */

		$this->assertEquals(true, $actual);
	}

	/**
	 * testInsertMysql
	 *
	 * @since 2.4.0
	 */

	public function testInsertMysql()
	{
		/* setup */

		$installer = new Installer();

		/* actual */

		$actual = $installer->insertMysql();

		/* compare */

		$this->assertEquals(true, $actual);
	}
}
