<?php

/**
 * contact install
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function contact_install()
{
	Redaxscript\Db::forTablePrefix('modules')
		->create()
		->set(array(
			'name' => 'Contact',
			'alias' => 'contact',
			'author' => 'Redaxmedia',
			'description' => 'Simple contact form',
			'version' => '2.4.0'
		))
		->save();
}

/**
 * contact uninstall
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function contact_uninstall()
{
	Redaxscript\Db::forTablePrefix('modules')->where('alias', 'contact')->findMany()->delete();
}

