<?php
namespace Redaxscript\Modules\LazyLoad;

use Redaxscript\Html;
use Redaxscript\Registry;

/**
 * lazy load images
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class LazyLoad extends Config
{
	/**
	 * array of the module
	 *
	 * @var array
	 */

	protected static $_moduleArray = array(
		'name' => 'Lazy load',
		'alias' => 'LazyLoad',
		'author' => 'Redaxmedia',
		'description' => 'Lazy load images',
		'version' => '3.0.0'
	);

	/**
	 * loaderStart
	 *
	 * @since 2.2.0
	 */

	public static function loaderStart()
	{
		global $loader_modules_styles, $loader_modules_scripts;
		$loader_modules_styles[] = 'modules/LazyLoad/assets/styles/lazy_load.css';
		$loader_modules_scripts[] = 'modules/LazyLoad/assets/scripts/init.js';
		$loader_modules_scripts[] = 'modules/LazyLoad/assets/scripts/lazy_load.js';
	}

	/**
	 * render
	 *
	 * @since 2.2.0
	 *
	 * @param mixed $src
	 * @param array $options
	 *
	 * @return string
	 */

	public static function render($src = null, $options = array())
	{
		$output = null;

		/* device related images */

		if (is_array($src))
		{
			/* process source */

			foreach ($src as $key => $value)
			{
				if (in_array($key, self::$_configArray['device']) && Registry::get('my' . ucfirst($key)))
				{
					$src = $value;
				}
			}
		}

		/* collect output */

		if (file_exists($src))
		{
			$imageElement = new Html\Element();
			$imageElement->init('img', array(
				'alt' => $options['alt'],
				'class' => self::$_configArray['className']['image'] . ' ' . $options['className'],
				'src' => self::$_configArray['placeholder']
			));

			/* collect output */

			$output = $imageElement->copy()->attr('data-src', $src);

			/* placeholder */

			if (self::$_configArray['placeholder'])
			{
				/* calculate image ratio */

				$imageDimensions = getimagesize($src);
				$imageRatio = $imageDimensions[1] / $imageDimensions[0] * 100;

				/* placeholder */

				$placeholderElement = new Html\Element();
				$placeholderElement->init('div', array(
					'class' => self::$_configArray['className']['placeholder'],
					'style' => 'padding-bottom:' . $imageRatio . '%'
				));

				/* collect output */

				$output = $placeholderElement->html($output);
			}

			/* noscript fallback */

			$output .= '<noscript>' . $imageElement . '</noscript>';
		}
		return $output;
	}
}
