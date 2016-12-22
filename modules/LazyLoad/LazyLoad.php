<?php
namespace Redaxscript\Modules\LazyLoad;

use Redaxscript\Head;
use Redaxscript\Html;

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

	public function adminPanelNotification()
	{
		return $this->getNotification();
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
			->appendFile('modules/LazyLoad/dist/styles/lazy-load.min.css');

		/* script */

		$script = Head\Script::getInstance();
		$script
			->init('foot')
			->appendFile('modules/LazyLoad/assets/scripts/init.js')
			->appendFile('modules/LazyLoad/dist/scripts/lazy-load.min.js');
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

	public function render($file = null, $optionArray = [])
	{
		$output = null;

		/* device related images */

		if (is_array($file))
		{
			/* process source */

			foreach ($file as $key => $value)
			{
				if (in_array($key, $this->_configArray['device']) && $this->_registry->get('my' . ucfirst($key)))
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
				'class' => $this->_configArray['className']['image'],
				'src' => $this->_configArray['placeholder']
			]);

			/* collect image output */

			$output = $imageElement
				->copy()
				->addClass($optionArray['className'])
				->attr('data-src', $file);

			/* placeholder */

			if ($this->_configArray['placeholder'])
			{
				/* calculate image ratio */

				$imageDimensions = getimagesize($file);
				$imageRatio = $imageDimensions[1] / $imageDimensions[0] * 100;

				/* placeholder */

				$placeholderElement = new Html\Element();
				$placeholderElement->init('div',
				[
					'class' => $this->_configArray['className']['placeholder'],
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
			$this->setNotification('error', $this->_language->get('file_not_found') . $this->_language->get('colon') . ' ' . $file . $this->_language->get('point'));
		}
		return $output;
	}
}
