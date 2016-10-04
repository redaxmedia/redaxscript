<?php
namespace Redaxscript\Modules\Disqus;

use Redaxscript\Head;
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
	 * renderStart
	 *
	 * @since 2.2.0
	 */

	public static function renderStart()
	{
		if (Registry::get('articleId'))
		{
			Registry::set('commentReplace', true);

			/* script */

			$script = Head\Script::getInstance();
			$script
				->init('foot')
				->appendFile(self::$_configArray['url'])
				->appendFile('modules/Disqus/assets/scripts/init.js');
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

		/* collect output */

		$output = $boxElement;
		echo $output;
	}
}
