<?php
namespace Redaxscript\Modules\LazyLoad;

use Redaxscript\Head;
use Redaxscript\Html;
use Redaxscript\Language;
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

	protected static $_moduleArray =
	[
		'name' => 'Lazy load',
		'alias' => 'LazyLoad',
		'author' => 'Redaxmedia',
		'description' => 'Lazy load images',
		'version' => '3.0.0'
	];

	/**
	 * adminPanelNotification
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public static function adminPanelNotification()
	{
		return self::getNotification();
	}

	/**
	 * renderStart
	 *
	 * @since 3.0.0
	 */

	public function renderStart()
	{
		/* link */

		$link = Head\Link::getInstance();
		$link
			->init()
			->appendFile('modules/LazyLoad/assets/styles/lazy_load.css');

		/* script */

		$script = Head\Script::getInstance();
		$script
			->init('foot')
			->appendFile('modules/LazyLoad/assets/scripts/init.js')
			->appendFile('modules/LazyLoad/assets/scripts/lazy_load.js');
	}

	/**
	 * render
	 *
	 * @since 2.2.0
	 *
	 * @param mixed $file
	 * @param array $optionArray
	 *
	 * @return string
	 */

	public static function render($file = null, $optionArray = [])
	{
		$output = null;

		/* device related images */

		if (is_array($file))
		{
			/* process source */

			foreach ($file as $key => $value)
			{
				if (in_array($key, self::$_configArray['device']) && Registry::get('my' . ucfirst($key)))
				{
					$file = $value;
				}
			}
		}

		/* collect output */

		if (file_exists($file))
		{
			$imageElement = new Html\Element();
			$imageElement->init('img',
			[
				'alt' => $optionArray['alt'],
				'class' => self::$_configArray['className']['image'],
				'src' => self::$_configArray['placeholder']
			]);

			/* collect image output */

			$output = $imageElement
				->copy()
				->addClass($optionArray['className'])
				->attr('data-src', $file);

			/* placeholder */

			if (self::$_configArray['placeholder'])
			{
				/* calculate image ratio */

				$imageDimensions = getimagesize($file);
				$imageRatio = $imageDimensions[1] / $imageDimensions[0] * 100;

				/* placeholder */

				$placeholderElement = new Html\Element();
				$placeholderElement->init('div',
				[
					'class' => self::$_configArray['className']['placeholder'],
					'style' => 'padding-bottom:' . $imageRatio . '%'
				]);

				/* collect output */

				$output = $placeholderElement->html($output);
			}

			/* noscript fallback */

			$output .= '<noscript>' . $imageElement . '</noscript>';
		}

		/* else handle notification */

		else
		{
			self::setNotification('error', Language::get('file_not_found') . Language::get('colon') . ' ' . $file . Language::get('point'));
		}
		return $output;
	}
}
