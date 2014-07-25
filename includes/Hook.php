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
				self::$_modules[] = $module->alias;
			}
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