<?php
namespace Redaxscript\Tests;

use Redaxscript\Installer;

/**
 * InstallerTest
 *
 * @since 2.4.0
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

	/**
	 * testCreatePgsql
	 *
	 * @since 2.4.0
	 */

	public function testCreatePgsql()
	{
		/* setup */

		$installer = new Installer();

		/* actual */

		$actual = $installer->createPgsql();

		/* compare */

		$this->assertEquals(true, $actual);
	}

	/**
	 * testInsertPgsql
	 *
	 * @since 2.4.0
	 */

	public function testInsertPgsql()
	{
		/* setup */

		$installer = new Installer();

		/* actual */

		$actual = $installer->insertPgsql();

		/* compare */

		$this->assertEquals(true, $actual);
	}

	/**
	 * testCreateSqlite
	 *
	 * @since 2.4.0
	 */

	public function testCreateSqlite()
	{
		/* setup */

		$installer = new Installer();

		/* actual */

		$actual = $installer->createSqlite();

		/* compare */

		$this->assertEquals(true, $actual);
	}

	/**
	 * testInsertSqlite
	 *
	 * @since 2.4.0
	 */

	public function testInsertSqlite()
	{
		/* setup */

		$installer = new Installer();

		/* actual */

		$actual = $installer->insertSqlite();

		/* compare */

		$this->assertEquals(true, $actual);
	}
}
