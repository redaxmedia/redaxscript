<?php
namespace Redaxscript\Modules\Disqus;

use Redaxscript\Element;
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

	protected static $_moduleArray = array(
		'name' => 'Disqus',
		'alias' => 'Disqus',
		'author' => 'Redaxmedia',
		'description' => 'Replace comments with disqus',
		'version' => '2.4.0',
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
		if (Registry::get('article'))
		{
			global $loader_modules_scripts;
			$loader_modules_scripts[] = 'modules/Disqus/scripts/init.js';
		}
	}

	/**
	 * renderStart
	 *
	 * @since 2.2.0
	 */

	public static function renderStart()
	{
		if (Registry::get('article'))
		{
			Registry::set('commentsReplace', true);
		}
	}

	/**
	 * commentsReplace
	 *
	 * @since 2.2.0
	*/

	public static function commentsReplace()
	{
		$divElement = new Element('div', array(
			'id' => self::$_config['id']
		));
		$scriptElement = new Element('script', array(
			'src' => self::$_config['url']
		));

		/* collect output */

		$output = $divElement . $scriptElement;
		echo $output;
	}
}