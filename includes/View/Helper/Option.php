<?php
namespace Redaxscript\View\Helper;

use PDO;

/**
 * helper class to provide various options
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category View
 * @author Henry Ruhs
 */

class Option
{
	/**
	 * get the database array
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public static function getDatabaseArray()
	{
		$databaseArray = array();

		/* process driver */

		foreach (PDO::getAvailableDrivers() as $driver)
		{
			if (is_dir('database/' . $driver))
			{
				$databaseArray[$driver] = $driver;
			}
		}
		return $databaseArray;
	}
}