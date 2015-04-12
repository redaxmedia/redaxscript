<?php

/**
 * fb group install
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function fb_group_install()
{
	Redaxscript\Db::forTablePrefix('modules')
		->create()
		->set(array(
			'name' => 'Facebook group',
			'alias' => 'fb_group',
			'author' => 'Redaxmedia',
			'description' => 'Integrate a Facebook group',
			'version' => '2.4.0'
		))
		->save();
}

/**
 * fb group uninstall
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function fb_group_uninstall()
{
	Redaxscript\Db::forTablePrefix('modules')->where('alias', 'fb_group')->findMany()->delete();
}

