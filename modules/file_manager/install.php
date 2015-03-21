<?php

/**
 * file manager install
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function file_manager_install()
{
	Redaxscript\Db::forTablePrefix('modules')
		->create()
		->set(array(
			'name' => 'File manager',
			'alias' => 'file_manager',
			'author' => 'Redaxmedia',
			'description' => 'Simple file management',
			'version' => '2.4.0',
			'status' => 1,
			'access' => 1
		))
		->save();
}

/**
 * file manager uninstall
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function file_manager_uninstall()
{
	Redaxscript\Db::forTablePrefix('modules')->where('alias', 'file_manager')->findMany()->delete();
}

