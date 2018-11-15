<?php
namespace Redaxscript\Module;

use Redaxscript\Config;
use Redaxscript\Db;
use Redaxscript\Filesystem;
use Redaxscript\Language;
use Redaxscript\Registry;
use Redaxscript\Request;
use Redaxscript\Validator;
use function array_merge;
use function call_user_func_array;
use function in_array;
use function is_array;
use function method_exists;
use function strlen;

/**
 * parent class to handle module hooks
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Module
 * @author Henry Ruhs
 */

class Hook
{
	/**
	 * instance of the registry class
	 *
	 * @var Registry
	 */

	protected static $_registry;

	/**
	 * instance of the request class
	 *
	 * @var Request
	 */

	protected static $_request;

	/**
	 * instance of the language class
	 *
	 * @var Language
	 */

	protected static $_language;

	/**
	 * instance of the config class
	 *
	 * @var Config
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
		$modulesFilesystem = new Filesystem\Filesystem();
		$modulesFilesystem->init('modules');
		$modulesFilesystemArray = $modulesFilesystem->getSortArray();
		$modulesInstalled = Db::forTablePrefix('modules')->where('status', 1)->findMany();

		/* process modules */

		foreach ($modulesInstalled as $module)
		{
			/* validate access */

			if (in_array($module->alias, $modulesFilesystemArray) && $accessValidator->validate($module->access, self::$_registry->get('myGroups')))
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

	public static function getModuleArray() : array
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

	public static function getEventArray() : array
	{
		return self::$_eventArray;
	}

	/**
	 * collect from the module hook
	 *
	 * @since 3.2.0
	 *
	 * @param string $eventName name of the module event
	 * @param array $parameterArray parameter of the module hook
	 *
	 * @return array
	 */

	public static function collect(string $eventName = null, array $parameterArray = []) : array
	{
		$collectArray = [];

		/* process modules */

		foreach (self::$_moduleArray as $moduleName)
		{
			$callArray = self::_call($moduleName, $eventName, $parameterArray);
			if (is_array($callArray))
			{
				$collectArray = array_merge($collectArray, $callArray);
			}
		}
		return $collectArray;
	}

	/**
	 * trigger the module hook
	 *
	 * @since 3.2.0
	 *
	 * @param string $eventName name of the module event
	 * @param array $parameterArray parameter of the module hook
	 *
	 * @return string|null
	 */

	public static function trigger(string $eventName = null, array $parameterArray = []) : ?string
	{
		$output = null;

		/* process modules */

		foreach (self::$_moduleArray as $moduleName)
		{
			$outputCall = self::_call($moduleName, $eventName, $parameterArray);
			if (strlen($outputCall))
			{
				$output .= $outputCall;
			}
		}
		return $output;
	}

	/**
	 * call the module
	 *
	 * @since 3.2.0
	 *
	 * @param string $moduleName
	 * @param string $eventName
	 * @param array $parameterArray
	 *
	 * @return string|array|null
	 */

	protected static function _call(string $moduleName = null, string $eventName = null, array $parameterArray = [])
	{
		$moduleClass = self::$_namespace . '\\' . $moduleName . '\\' . $moduleName;
		if (method_exists($moduleClass, $eventName))
		{
			self::$_eventArray[$eventName][$moduleName] = true;
			$module = new $moduleClass(self::$_registry, self::$_request, self::$_language, self::$_config);
			return call_user_func_array(
			[
				$module,
				$eventName
			], $parameterArray);
		}
		return null;
	}
}
