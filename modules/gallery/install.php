<?php

/**
 * gallery install
 *
 * @since 2.0.2
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function gallery_install()
{
	Redaxscript\Db::forTablePrefix('modules')
		->create()
		->set(array(
			'name' => 'Gallery',
			'alias' => 'gallery',
			'author' => 'Redaxmedia',
			'description' => 'Lightbox enhanced image gallery',
			'version' => '2.5.0'
		))
		->save();
}

/**
 * gallery uninstall
 *
 * @since 2.0.2
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function gallery_uninstall()
{
	Redaxscript\Db::forTablePrefix('modules')->where('alias', 'gallery')->findMany()->delete();
}
