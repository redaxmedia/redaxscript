<?php
namespace Redaxscript\Modules\ShareThis;

use Redaxscript\Html;
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
	 * array of the module
	 *
	 * @var array
	 */

	protected static $_moduleArray = [
		'name' => 'Share this',
		'alias' => 'ShareThis',
		'author' => 'Redaxmedia',
		'description' => 'Integrate social networks',
		'version' => '3.0.0'
	];

	/**
	 * loaderStart
	 *
	 * @since 2.2.0
	 */

	public static function loaderStart()
	{
		global $loader_modules_styles, $loader_modules_scripts;
		$loader_modules_styles[] = 'modules/ShareThis/assets/styles/share_this.css';
		$loader_modules_scripts[] = 'modules/ShareThis/assets/scripts/init.js';
		$loader_modules_scripts[] = 'modules/ShareThis/assets/scripts/share_this.js';
	}

	/**
	 * contentFragmentEnd
	 *
	 * @since 3.0.0
	 */

	public static function contentFragmentEnd()
	{
		if (Registry::get('lastTable') === 'articles')
		{
			$url = Registry::get('root') . Registry::get('parameterRoute') . Registry::get('fullRoute');
			return self::render($url);
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
		$output = null;
		if ($url)
		{
			/* html elements */

			$linkElement = new Html\Element();
			$linkElement->init('a',
			[
				'target' => '_blank'
			]);
			$listElement = new Html\Element();
			$listElement->init('ul',
			[
				'class' => self::$_configArray['className']['list']
			]);

			/* process network */

			foreach (self::$_configArray['network'] as $key => $value)
			{
				$output .= '<li>';
				$output .= $linkElement->attr(
				[
					'class' => self::$_configArray['className']['link'] . ' ' . $value['className'],
					'data-height' => $value['height'],
					'data-type' => $key,
					'data-width' => $value['width'],
					'href' => $value['url'] . $url,
				])
				->text($key);
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
