<?php

/**
 * parent class to handle module hooks
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Hook
 * @author Henry Ruhs
 */

class Redaxscript_Hook
{
	/**
	 * array of installed and enabled modules
	 *
	 * @var array
	 */

	protected static $_modules = array();

	/**
	 * init the class
	 *
	 * @since 2.2.0
	 *
	 * @param Redaxscript_Registry $registry instance of the registry class
	 */

	public static function init(Redaxscript_Registry $registry)
	{
		$accessValidator = new Redaxscript_Validator_Access();
		$modulesDirectory = new Redaxscript_Directory('modules');
		$modulesAvailable = $modulesDirectory->get();
		$modulesInstalled = Redaxscript_Db::forPrefixTable('modules')->where('status', 1)->findMany();

		/* proccess installed modules */

		foreach ($modulesInstalled as $module)
		{
			/* validate access */

			if (in_array($module->alias, $modulesAvailable) && $accessValidator->validate($module->access, $registry->get('myGroups')) === 1)
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

	public static function get()
	{
		return self::$_modules;
	}

	/**
	 * trigger the module hook
	 *
	 * @since 2.2.0
	 *
	 * @param string $hook name of the module hook
	 * @param array $parameter parameter of the module hook
	 *
	 * @return string $output
	 */

	public static function trigger($hook = null, $parameter = array())
	{
		$output = false;

		/* trigger module hooks */

		foreach (self::$_modules as $module)
		{
			$function = $module . '_' . $hook;
			$object = 'Redaxscript_Modules_' . mb_convert_case($module, MB_CASE_TITLE);
			$method = str_replace('_', '', mb_convert_case($hook, MB_CASE_TITLE));

			/* method exists */

			if (method_exists($object, $method))
			{
				$output .= call_user_func_array(array($object, $method), $parameter);
			}

			/* function exists */

			else if (function_exists($function))
			{
				$output .= call_user_func_array($function, $parameter);
			}
		}
		return $output;
	}
}