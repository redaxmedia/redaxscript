<?php
namespace Redaxscript\Modules\ShareThis;

use Redaxscript\Element;
use Redaxscript\Registry;

/**
 * integrate social networks
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
		'description' => 'Integrate social networks',
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
		$loader_modules_scripts[] = 'modules/ShareThis/scripts/init.js';
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
			$output = self::render($url);
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

	public static function render($url = null)
	{
		$output = '';
		if ($url)
		{
			/* html elements */

			$linkElement = new Element('a', array(
				'target' => '_blank'
			));
			$listElement = new Element('ul', array(
				'class' => self::$_config['className']['list']
			));

			/* process network */

			foreach (self::$_config['network'] as $key => $value)
			{
				$output .= '<li>';
				$output .= $linkElement->attr(array(
					'href' => $value['url'] . $url,
					'class' => self::$_config['className']['link'] . ' ' . $value['className'],
					'data-type' => $key,
					'data-height' => $value['height'],
					'data-width' => $value['width']
				))->text($key);
				$output .= '</li>';
			}

			/* collect list output */

			if ($output)
			{
				$output = $listElement->html($output);
			}
		}
		return $output;
	}
}
