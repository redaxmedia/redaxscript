<?php
namespace Redaxscript;

/**
 * parent class to install the database
 *
 * @since 2.4.0
 *
 * @category Installer
 * @package Redaxscript
 * @author Henry Ruhs
 */

class Installer
{
	/**
	 * create mysql tables
	 *
	 * @since 2.4.0
	 */

	public function createMysql()
	{
		return true;
	}

	/**
	 * insert mysql rows
	 *
	 * @since 2.4.0
	 */

	public function insertMysql()
	{
		return true;
	}

	/**
	 * create pgsql tables
	 *
	 * @since 2.4.0
	 */

	public function createPgsql()
	{
		return true;
	}

	/**
	 * insert pgsql rows
	 *
	 * @since 2.4.0
	 */

	public function insertPgsql()
	{
		return true;
	}

	/**
	 * create sqlite tables
	 *
	 * @since 2.4.0
	 */

	public function createSqlite()
	{
		return true;
	}

	/**
	 * insert sqlite rows
	 *
	 * @since 2.4.0
	 */

	public function insertSqlite()
	{
		return true;
	}
}
