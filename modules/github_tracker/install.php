<?php

/**
 * github tracker install
 *
 * @since 2.1.0
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function github_tracker_install()
{
	Redaxscript\Db::forTablePrefix('modules')
		->create()
		->set(array(
			'name' => 'Github tracker',
			'alias' => 'github_tracker',
			'author' => 'Redaxmedia',
			'description' => 'Integrate milestones and issues from Github',
			'version' => '2.4.0'
		))
		->save();
}

/**
 * github tracker uninstall
 *
 * @since 2.1.0
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function github_tracker_uninstall()
{
	Redaxscript\Db::forTablePrefix('modules')->where('alias', 'github_tracker')->findMany()->delete();
}

