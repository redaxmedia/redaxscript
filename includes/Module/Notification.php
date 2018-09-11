<?php
namespace Redaxscript\Module;

/**
 * children class to create a module with notification
 *
 * @since 3.3.0
 *
 * @package Redaxscript
 * @category Module
 * @author Henry Ruhs
 */

class Notification extends Module
{
	/**
	 * array of the notification
	 *
	 * @var array
	 */

	protected static $_notificationArray = [];

	/**
	 * get the message from notification
	 *
	 * @since 3.0.0
	 *
	 * @param string $type type of the notification
	 *
	 * @return array|null
	 */

	public function getNotification(string $type = null) : ?array
	{
		if (is_array(self::$_notificationArray) && array_key_exists($type, self::$_notificationArray))
		{
			return self::$_notificationArray[$type];
		}
		else if (!$type)
		{
			return self::$_notificationArray;
		}
		return null;
	}

	/**
	 * set the message to notification
	 *
	 * @since 3.0.0
	 *
	 * @param string $type type of the notification
	 * @param string|array $message message of the notification
	 */

	public function setNotification(string $type = null, $message = null)
	{
		$moduleName = static::$_moduleArray['name'];
		static::$_notificationArray[$type][$moduleName][] = $message;
	}

	/**
	 * clear the notification
	 *
	 * @since 3.0.0
	 *
	 * @param string $type type of the notification
	 */

	public function clearNotification(string $type = null)
	{
		$moduleName = static::$_moduleArray['name'];
		static::$_notificationArray[$type][$moduleName] = null;
	}
}
