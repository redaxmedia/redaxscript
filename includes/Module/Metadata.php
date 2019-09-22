<?php
namespace Redaxscript\Module;

/**
 * children class to create a module with metadata
 *
 * @since 4.1.0
 *
 * @package Redaxscript
 * @category Module
 * @author Henry Ruhs
 */

class Metadata extends Module
{
	/**
	 * array of the dashboard
	 *
	 * @var array
	 */

	protected static $_dashboardArray = [];

	/**
	 * array of the notification
	 *
	 * @var array
	 */

	protected static $_notificationArray = [];

	/**
	 * get the dashboard array
	 *
	 * @since 4.1.0
	 *
	 * @return array
	 */

	public function getDashboardArray() : array
	{
		return self::$_dashboardArray;
	}

	/**
	 * get the notification array
	 *
	 * @since 4.1.0
	 *
	 * @return array
	 */

	public function getNotificationArray() : array
	{
		return self::$_notificationArray;
	}

	/**
	 * set the dashboard
	 *
	 * @since 4.1.0
	 *
	 * @param string $content content of the dashboard
	 * @param int $column column of the dashboard
	 */

	public function setDashboard(string $content = null, int $column = 1) : void
	{
		$moduleName = static::$_moduleArray['name'];
		static::$_dashboardArray[$moduleName][] =
		[
			'content' => $content,
			'column' => $column
		];
	}

	/**
	 * set the notification
	 *
	 * @since 4.1.0
	 *
	 * @param string $type type of the notification
	 * @param string|array $message message of the notification
	 */

	public function setNotification(string $type = null, $message = null) : void
	{
		$moduleName = static::$_moduleArray['name'];
		static::$_notificationArray[$type][$moduleName][] = $message;
	}

	/**
	 * clear the dashboard array
	 *
	 * @since 4.1.0
	 */

	public function clearDashboard() : void
	{
		$moduleName = static::$_moduleArray['name'];
		static::$_notificationArray[$moduleName] = null;
	}

	/**
	 * clear the notification array
	 *
	 * @since 4.1.0
	 *
	 * @param string $type type of the notification
	 */

	public function clearNotification(string $type = null) : void
	{
		$moduleName = static::$_moduleArray['name'];
		static::$_notificationArray[$type][$moduleName] = null;
	}
}
