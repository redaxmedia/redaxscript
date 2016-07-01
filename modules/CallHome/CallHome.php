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
	 * array of the module
	 *
	 * @var array
	 */

	protected static $_moduleArray = array(
		'name' => 'Call home',
		'alias' => 'CallHome',
		'author' => 'Redaxmedia',
		'description' => 'Provide version and news updates',
		'version' => '3.0.0'
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
			$loader_modules_scripts[] = 'modules/CallHome/assets/scripts/init.js';
			$loader_modules_scripts[] = 'modules/CallHome/assets/scripts/call_home.js';
		}
	}

	/**
	 * scriptStart
	 *
	 * @since 2.2.0
	 */

	public static function scriptStart()
	{
		if (Registry::get('loggedIn') === Registry::get('token') && Registry::get('firstParameter') === 'admin')
		{
			$output = '<script src="//google-analytics.com/analytics.js"></script>';
			echo $output;
		}
	}

	/**
	 * adminPanelNotification
	 *
	 * @since 3.0.0
	 */

	public static function adminPanelNotification()
	{
		$output = null;
		$aliasFilter = new Filter\Alias();
		$version = $aliasFilter->sanitize(Language::get('version', '_package'));
		$urlVersion = 'http://service.redaxscript.com/version/' . $version;
		$urlNews = 'http://service.redaxscript.com/news/' . $version;

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
		return $output;
	}
}
