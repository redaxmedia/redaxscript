<?php
namespace Redaxscript\Modules\ShareThis;

use Redaxscript\Registry;

/**
 * integrate social buttons
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class ShareThis extends Config
{
	/**
	 * custom module setup
	 *
	 * @var array
	 */

	protected static $_module = array(
		'name' => 'Share this',
		'alias' => 'ShareThis',
		'author' => 'Redaxmedia',
		'description' => 'Integrate social buttons',
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
		global $loader_modules_styles, $loader_modules_scripts;
		$loader_modules_styles[] = 'modules/ShareThis/styles/share_this.css';
		$loader_modules_scripts[] = 'modules/ShareThis/scripts/startup.js';
		$loader_modules_scripts[] = 'modules/ShareThis/scripts/share_this.js';
	}

	/**
	 * articleEnd
	 *
	 * @since 2.2.0
	 */

	public static function articleEnd()
	{
		if (Registry::get('lastTable') === 'articles')
		{
			$url = Registry::get('root') . '/' . Registry::get('rewriteRoute') . Registry::get('fullRoute');
			$output = self::_render($url);
			return $output;
		}
	}

	/**
	 * render
	 *
	 * @since 2.2.0
	 *
	 * @param string $url
	 *
	 * @return string
	 */

	protected static function _render($url = null)
	{
		/* collect output */

		if ($url)
		{
			$output = '<ul class="' . self::$_config['className']['list'] . '">';

			/* handle each network */

			foreach (self::$_config['networks'] as $key => $value)
			{
				$output .= '<li><a href="' . $value['url'] . $url . '" class="' . self::$_config['className']['link'] . $value['className'] . '" title="' . ucfirst($key) . '"' . self::$_config['attribute']['link'] . $value['attribute'] . '>' . ucfirst($key) . '</a></li>';
			}
			$output .= '</ul>';
			return $output;
		}
	}
}
