<?php
namespace Redaxscript;

/**
 * parent class to build a module
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

	protected static $_moduleArray = array(
		status => 1,
		access => 0
	);

	/**
	 * constructor of the class
	 *
	 * @since 2.2.0
	 *
	 * @param array $moduleArray custom module setup
	 */

	public function __construct($moduleArray = array())
	{
		if (is_array($moduleArray))
		{
			static::$_moduleArray = array_merge(static::$_moduleArray, $moduleArray);
		}
	}

	/**
	 * init the class
	 *
	 * @since 2.2.0
	 */

	public function init()
	{
		if (isset(static::$_moduleArray['alias']))
		{
			$language = Language::getInstance();
			$language->load(array(
				'modules/' . static::$_moduleArray['alias'] . '/languages/en.json',
				'modules/' . static::$_moduleArray['alias'] . '/languages/' . Registry::get('language') . '.json'
			));
		}
	}

	/**
	 * install the module
	 *
	 * @since 2.2.0
	 */

	public function install()
	{
		if (isset(static::$_moduleArray['name']) && isset(static::$_moduleArray['alias']))
		{
			$module = Db::forTablePrefix('modules')->create();
			$module->set(static::$_moduleArray);
			$module->save();
		}
	}

	/**
	 * uninstall the module
	 *
	 * @since 2.2.0
	 */

	public function uninstall()
	{
		if (isset(static::$_moduleArray['alias']))
		{
			Db::forTablePrefix('modules')->where('alias', static::$_moduleArray['alias'])->deleteMany();
		}
	}
}