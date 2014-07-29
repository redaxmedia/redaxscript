<?php

/**
 * parent class to build a module class
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Module
 * @author Henry Ruhs
 */

class Redaxscript_Module
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
			self::$_module = array_merge(self::$_module, $module);
		}
	}

	/**
	 * install the module
	 *
	 * @since 2.2.0
	 *
	 * @param string $alias alias of the module
	 */

	public function install()
	{
		$module = Redaxscript_Db::forPrefixTable('modules')->create();
		$module->set(self::$_module);
		$module->save();
	}

	/**
	 * uninstall the module
	 *
	 * @since 2.2.0
	 */

	public function uninstall()
	{
		Redaxscript_Db::forPrefixTable('modules')->where('alias', $this->_module['alias'])->deleteMany();
	}
}