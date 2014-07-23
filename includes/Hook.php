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
	 * array of installed modules
	 *
	 * @var array
	 */

	protected static $_modules = array();

	/**
	 * init the class
	 *
	 * @since 2.2.0
	 */

	public static function init()
	{
		$modulesDirectory = New Redaxscript_Directory('modules');
		$modulesAvailable = $modulesDirectory->get();
		$modulesInstalled = Redaxscript_Db::forPrefixTable('modules')->findMany();

		/* intersect available and installed modules */

		if (is_array($modulesAvailable) && is_array($modulesInstalled))
		{
			self::$_modules = array_intersect($modulesAvailable, $modulesInstalled);
		}
	}

	/**
	 * trigger the module hook
	 *
	 * @since 2.2.0
	 *
	 * @param string $hook name of the module hook
	 *
	 * @return string $output
	 */

	public static function trigger($hook = null)
	{
		$output = false;

		/* trigger event hooks */

		foreach (self::$_modules as $module)
		{
			$function = $module . '_' . $hook;

			/* function exists */

			if (function_exists($function))
			{
				$output .= call_user_func($function);
			}
		}
		return $output;
	}
}