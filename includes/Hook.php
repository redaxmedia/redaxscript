<?php
namespace Redaxscript;

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
	 * module namespace
	 *
	 * @var string
	 */

	protected static $_namespace = 'Redaxscript\Modules\\';

	/**
	 * hook delimiter
	 *
	 * @var string
	 */

	protected static $_delimiter = '_';

	/**
	 * array of installed and enabled modules
	 *
	 * @var array
	 */

	protected static $_modules = array();

	/**
	 * array of triggered events
	 *
	 * @var array
	 */

	protected static $_events = array();

	/**
	 * constructor of the class
	 *
	 * @since 2.6.0
	 *
	 * @param Registry $registry instance of the registry class
	 */

	public static function construct(Registry $registry)
	{
		self::$_registry = $registry;
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

		/* process installed modules */

		foreach ($modulesInstalled as $module)
		{
			/* validate access */

			if (in_array($module->alias, $modulesAvailable) && $accessValidator->validate($module->access, self::$_registry->get('myGroups')) === Validator\ValidatorInterface::PASSED)
			{
				self::$_modules[$module->alias] = $module->alias;
			}
		}
	}

	/**
	 * get the modules array
	 *
	 * @since 2.2.0
	 *
	 * @return mixed
	 */

	public static function getModules()
	{
		return self::$_modules;
	}

	/**
	 * get the events array
	 *
	 * @since 2.2.0
	 *
	 * @return mixed
	 */

	public static function getEvents()
	{
		return self::$_events;
	}

	/**
	 * trigger the module hook
	 *
	 * @since 3.0.0
	 *
	 * @param string $event name of the module event
	 * @param array $parameter parameter of the module hook
	 *
	 * @return string $output
	 */

	public static function trigger($event = null, $parameter = array())
	{
		$output = false;

		/* trigger event */
		echo '<div>' . $event . '</div>';
		foreach (self::$_modules as $module)
		{
			$object = self::$_namespace . $module . '\\' . $module;

			/* method exists */

			if (method_exists($object, $event))
			{
				$output .= call_user_func_array(array(
					$object,
					$event
				), $parameter);
				self::$_events[$event][] = $module;
			}
		}
		return $output;
	}
}
