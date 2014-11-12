<?php
namespace Redaxscript\Modules\CallHome;

use Redaxscript\Filter;
use Redaxscript\Language;
use Redaxscript\Module;
use Redaxscript\Registry;

/**
 * provide version and news updates
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class CallHome extends Module
{
	/**
	 * custom module setup
	 *
	 * @var array
	 */

	protected static $_module = array(
		'name' => 'Call home',
		'alias' => 'CallHome',
		'author' => 'Redaxmedia',
		'description' => 'Provide version and news updates',
		'version' => '2.2.0',
		'status' => 1,
		'access' => 0
	);

	/**
	 * loaderStart
	 *
	 * @since 2.2.0
	 */

	public static function loaderStart()
	{
		if (Registry::get('loggedIn') === Registry::get('token') && Registry::get('firstParameter') === 'admin')
		{
			global $loader_modules_scripts;
			$loader_modules_scripts[] = 'modules/CallHome/scripts/startup.js';
			$loader_modules_scripts[] = 'modules/CallHome/scripts/call_home.js';
		}
	}

	/**
	 * scriptsStart
	 *
	 * @since 2.2.0
	 */

	public static function scriptsStart()
	{
		if (Registry::get('loggedIn') === Registry::get('token') && Registry::get('firstParameter') === 'admin')
		{
			$output = '<script src="//google-analytics.com/ga.js"></script>';
			echo $output;
		}
	}

	/**
	 * adminNotificationStart
	 *
	 * @since 2.2.0
	 */

	public static function adminNotificationStart()
	{
		$output = '';
		$aliasFilter = new Filter\Alias();
		$urlVersion = 'http://service.redaxscript.com/version/' . $aliasFilter->sanitize(Language::get('version', '_package'));
		$urlNews = 'http://service.redaxscript.com/news';

		/* get contents */

		$contentsVersion = file_get_contents($urlVersion);
		$contentsNews = file_get_contents($urlNews);

		/* collect version output */

		if ($contentsVersion)
		{
			$output = $contentsVersion;
		}

		/* collect news output */

		if ($contentsNews)
		{
			$output .= $contentsNews;
		}
		echo $output;
	}
}