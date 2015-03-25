<?php
namespace Redaxscript;

/**
 * parent class to install a database
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
}