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
	 * common module setup
	 *
	 * @var array
	 */

	protected static $_module = array(
		'name' => null,
		'alias' => null,
		'author' => 'Redaxmedia',
		'description' => null,
		'version' => null,
		'status' => 1,
		'access' => 0
	);

	/**
	 * constructor of the class
	 *
	 * @since 2.2.0
	 *
	 * @param array $module custom module setup
	 */

	public function __construct($module = array())
	{
		if (is_array($module))
		{
			static::$_module = array_merge(static::$_module, $module);
		}
	}

	/**
	 * install the module
	 *
	 * @since 2.2.0
	 */

	public function install()
	{
		if (isset(static::$_module['name']) && isset(static::$_module['alias']))
		{
			$module = Db::forPrefixTable('modules')->create();
			$module->set(static::$_module);
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
		if (isset(static::$_module['alias']))
		{
			Db::forPrefixTable('modules')->where('alias', static::$_module['alias'])->deleteMany();
		}
	}
}