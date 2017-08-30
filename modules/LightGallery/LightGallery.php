<?php
namespace Redaxscript\Modules\LightGallery;

use Redaxscript\Filesystem;
use Redaxscript\Head;
use Redaxscript\Html;

/**
 * javascript powered light gallery
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class LightGallery extends Config
{
	/**
	 * array of the module
	 *
	 * @var array
	 */

	protected static $_moduleArray =
	[
		'name' => 'Light Gallery',
		'alias' => 'LightGallery',
		'author' => 'Redaxmedia',
		'description' => 'Javascript powered light gallery',
		'version' => '3.2.3'
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
			->appendFile('https://cdnjs.cloudflare.com/ajax/libs/lightgallery/1.3.9/css/lightgallery.min.css')
			->appendFile('modules/LightGallery/dist/styles/light-gallery.min.css');

		/* script */

		$script = Head\Script::getInstance();
		$script
			->init('foot')
			->appendFile('https://cdnjs.cloudflare.com/ajax/libs/lightgallery/1.3.9/js/lightgallery.min.js')
			->appendFile('modules/LightGallery/assets/scripts/init.js')
			->appendFile('modules/LightGallery/dist/scripts/light-gallery.min.js');
	}

	/**
	 * render
	 *
	 * @since 3.0.0
	 *
	 * @param string $directory
	 * @param array $optionArray
	 *
	 * @return string
	 */

	public function render($directory = null, $optionArray = [])
	{
		$output = null;
		$outputItem = null;

		/* html elements */

		$listElement = new Html\Element();
		$listElement->init('ul',
		[
			'class' => $this->_configArray['className']['list']
		]);

		/* handle notification */

		if (!is_dir($directory))
		{
			$this->setNotification('error', $this->_language->get('directory_not_found') . $this->_language->get('colon') . ' ' . $directory . $this->_language->get('point'));
		}

		/* else collect item */

		else
		{
			/* remove as needed */

			if ($optionArray['command'] === 'remove')
			{
				$this->_removeThumb($directory);
			}

			/* create as needed */

			if ($optionArray['command'] === 'create' || !is_dir($directory . DIRECTORY_SEPARATOR . $this->_configArray['thumbDirectory']))
			{
				$this->_createThumb($directory, $optionArray);
			}
			$outputItem .= $this->_renderItem($directory, $optionArray);

			/* collect list output */

			if ($outputItem)
			{
				$output = $listElement->html($outputItem);
			}
		}
		return $output;
	}

	/**
	 * renderItem
	 *
	 * @since 2.6.0
	 *
	 * @param string $directory
	 * @param array $optionArray
	 *
	 * @return string
	 */

	public function _renderItem($directory = null, $optionArray = [])
	{
		$outputItem = null;

		/* html elements */

		$imageElement = new Html\Element();
		$imageElement->init('img',
		[
			'class' => $this->_configArray['className']['image']
		]);
		$linkElement = new Html\Element();
		$linkElement->init('a');
		$itemElement = new Html\Element();
		$itemElement->init('li');

		/* gallery filesystem */

		$galleryFilesystem = new Filesystem\Filesystem();
		$galleryFilesystem->init($directory, false,
		[
			$this->_configArray['thumbDirectory']
		]);
		$galleryFilesystemArray = $galleryFilesystem->getSortArray();

		/* adjust order */

		if ($optionArray['order'] === 'desc')
		{
			$galleryFilesystemArray = array_reverse($galleryFilesystemArray);
		}

		/* process filesystem */

		foreach ($galleryFilesystemArray as $value)
		{
			$imagePath = $directory . DIRECTORY_SEPARATOR . $value;
			$thumbPath = $directory . DIRECTORY_SEPARATOR . $this->_configArray['thumbDirectory'] . DIRECTORY_SEPARATOR . $value;

			/* collect item output */

			$outputItem .= $itemElement
				->clear()
				->html(
					$linkElement
						->copy()
						->attr('href', $imagePath)
						->html(
							$imageElement
								->copy()
								->attr(
								[
									'src' => $thumbPath,
									'alt' => $value
								])
						)
				);
		}
		return $outputItem;
	}

	/**
	 * removeThumb
	 *
	 * @since 3.0.0
	 *
	 * @param string $directory
	 */

	protected function _removeThumb($directory = null)
	{
		$galleryFilesystem = new Filesystem\Directory();
		$galleryFilesystem->init($directory, true);
		$galleryFilesystem->removeDirectory($this->_configArray['thumbDirectory']);
	}

	/**
	 * createThumb
	 *
	 * @since 3.0.0
	 *
	 * @param string $directory
	 * @param array $optionArray
	 */

	protected function _createThumb($directory = null, $optionArray = [])
	{
		/* gallery filesystem */

		$galleryFilesystem = new Filesystem\Directory();
		$galleryFilesystem->init($directory, false,
		[
			$this->_configArray['thumbDirectory']
		]);
		$galleryFilesystem->createDirectory($this->_configArray['thumbDirectory']);
		$galleryFilesystemArray = $galleryFilesystem->getSortArray();

		/* handle notification */

		if (!chmod($directory . DIRECTORY_SEPARATOR . $this->_configArray['thumbDirectory'], 0777))
		{
			$this->setNotification('error', $this->_language->get('directory_permission_grant') . $this->_language->get('colon') . ' ' . $directory . DIRECTORY_SEPARATOR . $this->_configArray['thumbDirectory'] . $this->_language->get('point'));
		}

		/* else process filesystem */

		else
		{
			foreach ($galleryFilesystemArray as $value)
			{
				$imagePath = $directory . DIRECTORY_SEPARATOR . $value;
				$imageExtension = strtolower(pathinfo($value, PATHINFO_EXTENSION));
				$thumbPath = $directory . DIRECTORY_SEPARATOR . $this->_configArray['thumbDirectory'] . DIRECTORY_SEPARATOR . $value;

				/* switch extension */

				switch ($imageExtension)
				{
					case 'gif':
						$image = imagecreatefromgif($imagePath);
						break;
					case 'jpg':
						$image = imagecreatefromjpeg($imagePath);
						break;
					case 'png':
						$image = imagecreatefrompng($imagePath);
						break;
					default:
						$image = null;
				}

				/* source and dist */

				$sourceArray = $this->_calcSource($imagePath);
				$distArray = $this->_calcDist($sourceArray, $optionArray);

				/* create thumb files */

				$thumb = imagecreatetruecolor($distArray['width'], $distArray['height']);
				imagecopyresampled($thumb, $image, 0, 0, 0, 0, $distArray['width'], $distArray['height'], $sourceArray['width'], $sourceArray['height']);
				imagejpeg($thumb, $thumbPath, $distArray['quality']);

				/* destroy image */

				imagedestroy($thumb);
				imagedestroy($image);
			}
		}
	}

	/**
	 * calcSource
	 *
	 * @param string $imagePath
	 *
	 * @return array
	 */

	protected function _calcSource($imagePath = null)
	{
		$sourceArray['dimensions'] = getimagesize($imagePath);
		$sourceArray['height'] = $sourceArray['dimensions'][1];
		$sourceArray['width'] = $sourceArray['dimensions'][0];
		return $sourceArray;
	}

	/**
	 * calcDist
	 *
	 * @param array $sourceArray
	 * @param array $optionArray
	 *
	 * @return array
	 */

	protected function _calcDist($sourceArray = [], $optionArray = [])
	{
		$distArray['height'] = is_array($optionArray) && array_key_exists('height', $optionArray) ? $optionArray['height'] : $this->_configArray['height'];
		$distArray['quality'] = is_array($optionArray) && array_key_exists('quality', $optionArray) ? $optionArray['quality'] : $this->_configArray['quality'];

		/* calculate */

		if ($distArray['height'])
		{
			$distArray['scaling'] = $distArray['height'] / $sourceArray['height'] * 100;
		}
		else
		{
			$distArray['height'] = round($distArray['scaling'] / 100 * $sourceArray['height']);
		}
		$distArray['width'] = round($distArray['scaling'] / 100 * $sourceArray['width']);
		return $distArray;
	}
}
