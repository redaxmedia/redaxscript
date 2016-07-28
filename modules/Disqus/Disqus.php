<?php
namespace Redaxscript\Modules\Disqus;

use Redaxscript\Html;
use Redaxscript\Registry;

/**
 * replace comments with disqus
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class Disqus extends Config
{
	/**
	 * array of the module
	 *
	 * @var array
	 */

	protected static $_moduleArray =
	[
		'name' => 'Disqus',
		'alias' => 'Disqus',
		'author' => 'Redaxmedia',
		'description' => 'Replace comments with disqus',
		'version' => '3.0.0'
	];

	/**
	 * loaderStart
	 *
	 * @since 2.2.0
	 */

	public static function loaderStart()
	{
		if (Registry::get('articleId'))
		{
			global $loader_modules_scripts;
			$loader_modules_scripts[] = 'modules/Disqus/assets/scripts/init.js';
		}
	}

	/**
	 * renderStart
	 *
	 * @since 2.2.0
	 */

	public static function renderStart()
	{
		if (Registry::get('articleId'))
		{
			Registry::set('commentReplace', true);
		}
	}

	/**
	 * commentReplace
	 *
	 * @since 2.2.0
	*/

	public static function commentReplace()
	{
		$boxElement = new Html\Element();
		$boxElement->init('div',
		[
			'id' => self::$_configArray['id']
		]);
		$scriptElement = new Html\Element();
		$scriptElement->init('script',
		[
			'src' => self::$_configArray['url']
		]);

		/* collect output */

		$output = $boxElement . $scriptElement;
		echo $output;
	}
}
