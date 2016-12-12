<?php
namespace Redaxscript;

/**
 * parent class to create a module
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Module
 * @author Henry Ruhs
 */

class Module
{
	/**
	 * array of the module
	 *
	 * @var array
	 */

	protected static $_moduleArray =
	[
		'status' => 1,
		'access' => null
	];

	/**
	 * array of the notification
	 *
	 * @var array
	 */

	protected static $_notificationArray = [];

	/**
	 * init the class
	 *
	 * @since 2.4.0
	 *
	 * @param array $moduleArray custom module setup
	 */

	public function init($moduleArray = [])
	{
		/* merge module setup */

		if (is_array($moduleArray))
		{
			static::$_moduleArray = array_merge(static::$_moduleArray, $moduleArray);
		}

		/* load the language */

		if (array_key_exists('alias', static::$_moduleArray))
		{
			$registry = Registry::getInstance();
			$language = Language::getInstance();
			$language->load(
			[
				'modules/' . static::$_moduleArray['alias'] . '/languages/en.json',
				'modules/' . static::$_moduleArray['alias'] . '/languages/' . $registry->get('language') . '.json'
			]);
		}
	}

	/**
	 * get message from notification
	 *
	 * @since 3.0.0
	 *
	 * @param string $type type of the notification
	 *
	 * @return mixed
	 */

	public function getNotification($type = null)
	{
		if (array_key_exists($type, self::$_notificationArray))
		{
			return self::$_notificationArray[$type];
		}
		else if (!$type)
		{
			return self::$_notificationArray;
		}
		return false;
	}

	/**
	 * set message to notification
	 *
	 * @since 3.0.0
	 *
	 * @param string $type type of the notification
	 * @param mixed $message message of the notification
	 */

	public function setNotification($type = null, $message = null)
	{
		$moduleName = static::$_moduleArray['name'];
		static::$_notificationArray[$type][$moduleName][] = $message;
	}

	/**
	 * install the module
	 *
	 * @since 2.6.0
	 */

	public function install()
	{
		if (array_key_exists('alias', static::$_moduleArray))
		{
			$module = Db::forTablePrefix('modules')->create();
			$module->set(static::$_moduleArray);
			$module->save();

			/* create from sql */

			$directory = 'modules/' . static::$_moduleArray['alias'] . '/database';
			if (is_dir($directory))
			{
				$installer = new Installer(Language::getInstance(), Config::getInstance());
				$installer->init($directory);
				$installer->rawCreate();
			}
		}
	}

	/**
	 * uninstall the module
	 *
	 * @since 2.6.0
	 */

	public function uninstall()
	{
		if (array_key_exists('alias', static::$_moduleArray))
		{
			Db::forTablePrefix('modules')->where('alias', static::$_moduleArray['alias'])->deleteMany();

			/* drop from sql */

			$directory = 'modules/' . static::$_moduleArray['alias'] . '/database';
			if (is_dir($directory))
			{
				$installer = new Installer(Language::getInstance(), Config::getInstance());
				$installer->init($directory);
				$installer->rawDrop();
			}
		}
	}
}