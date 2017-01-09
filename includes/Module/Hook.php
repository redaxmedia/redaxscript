<?php
namespace Redaxscript\Module;

use Redaxscript\Config;
use Redaxscript\Db;
use Redaxscript\Directory;
use Redaxscript\Language;
use Redaxscript\Registry;
use Redaxscript\Request;
use Redaxscript\Validator;

/**
 * parent class to handle module hooks
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Hook
 * @author Henry Ruhs
 */

class Hook
{
	/**
	 * instance of the registry class
	 *
	 * @var object
	 */

	protected static $_registry;

	/**
	 * instance of the request class
	 *
	 * @var object
	 */

	protected static $_request;

	/**
	 * instance of the language class
	 *
	 * @var object
	 */

	protected static $_language;

	/**
	 * instance of the config class
	 *
	 * @var object
	 */

	protected static $_config;

	/**
	 * module namespace
	 *
	 * @var string
	 */

	protected static $_namespace = 'Redaxscript\Modules';

	/**
	 * array of installed and enabled modules
	 *
	 * @var array
	 */

	protected static $_moduleArray = [];

	/**
	 * array of triggered events
	 *
	 * @var array
	 */

	protected static $_eventArray = [];

	/**
	 * constructor of the class
	 *
	 * @since 3.0.0
	 *
	 * @param Registry $registry instance of the registry class
	 * @param Request $request instance of the request class
	 * @param Language $language instance of the language class
	 * @param Config $config instance of the config class
	 */

	public static function construct(Registry $registry, Request $request, Language $language, Config $config)
	{
		self::$_registry = $registry;
		self::$_request = $request;
		self::$_language = $language;
		self::$_config = $config;
	}

	/**
	 * init the class
	 *
	 * @since 2.6.0
	 */

	public static function init()
	{
		$accessValidator = new Validator\Access();
		$modulesDirectory = new Directory();
		$modulesDirectory->init('modules');
		$modulesAvailable = $modulesDirectory->getArray();
		$modulesInstalled = Db::forTablePrefix('modules')->where('status', 1)->findMany();

		/* process modules */

		foreach ($modulesInstalled as $module)
		{
			/* validate access */

			if (in_array($module->alias, $modulesAvailable) && $accessValidator->validate($module->access, self::$_registry->get('myGroups')) === Validator\ValidatorInterface::PASSED)
			{
				self::$_moduleArray[$module->alias] = $module->alias;
			}
		}
	}

	/**
	 * get the module array
	 *
	 * @since 2.2.0
	 *
	 * @return array
	 */

	public static function getModuleArray()
	{
		return self::$_moduleArray;
	}

	/**
	 * get the event array
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public static function getEventArray()
	{
		return self::$_eventArray;
	}

	/**
	 * collect from module hook
	 *
	 * @since 3.0.0
	 *
	 * @param string $event name of the module event
	 * @param array $parameterArray parameter of the module hook
	 *
	 * @return array
	 */

	public static function collect($event = null, $parameterArray = [])
	{
		$outputArray = [];

		/* process modules */

		foreach (self::$_moduleArray as $module)
		{
			$moduleClass = self::$_namespace . '\\' . $module . '\\' . $module;
			self::$_eventArray[$event][$module] = false;

			/* method exists */

			if (method_exists($moduleClass, $event))
			{
				self::$_eventArray[$event][$module] = true;
				$module = new $moduleClass(self::$_registry, self::$_request, self::$_language, self::$_config);
				$outputArray = array_merge($outputArray, call_user_func_array(
				[
					$module,
					$event
				], $parameterArray));
			}
		}
		return $outputArray;
	}

	/**
	 * trigger the module hook
	 *
	 * @since 3.0.0
	 *
	 * @param string $event name of the module event
	 * @param array $parameterArray parameter of the module hook
	 *
	 * @return mixed
	 */

	public static function trigger($event = null, $parameterArray = [])
	{
		$output = null;

		/* process modules */

		foreach (self::$_moduleArray as $module)
		{
			$moduleClass = self::$_namespace . '\\' . $module . '\\' . $module;

			self::$_eventArray[$event][$module] = false;

			/* method exists */

			if (method_exists($moduleClass, $event))
			{
				self::$_eventArray[$event][$module] = true;
				$module = new $moduleClass(self::$_registry, self::$_request, self::$_language, self::$_config);
				$output .= call_user_func_array(
				[
					$module,
					$event
				], $parameterArray);
			}
		}
		return $output;
	}
}
