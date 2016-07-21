<?php
namespace Redaxscript\Modules\Gallery;

use Redaxscript\Db;
use Redaxscript\Directory;
use Redaxscript\Html;

/**
 * lightbox enhanced image gallery
 *
 * @since 2.6.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class Gallery extends Config
{
	/**
	 * array of the module
	 *
	 * @var array
	 */

	protected static $_moduleArray = array(
		'name' => 'Gallery',
		'alias' => 'Gallery',
		'author' => 'Redaxmedia',
		'description' => 'Lightbox enhanced image gallery',
		'version' => '3.0.0'
	);

	/**
	 * loaderStart
	 *
	 * @since 2.6.0
	 */

	public static function loaderStart()
	{
		global $loader_modules_styles, $loader_modules_scripts;
		$loader_modules_styles[] = 'modules/Gallery/assets/styles/gallery.css';
		$loader_modules_scripts[] = 'modules/Gallery/assets/scripts/init.js';
		$loader_modules_scripts[] = 'modules/Gallery/assets/scripts/gallery.js';
	}

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
	 * render
	 *
	 * @since 3.0.0
	 *
	 * @param string $directory
	 * @param array $optionArray
	 *
	 * @return string
	 */

	public static function render($directory = null, $optionArray = array())
	{
		$output = null;
		$outputItem = null;

		/* html elements */

		$listElement = new Html\Element();
		$listElement->init('ul', array(
			'class' => self::$_configArray['className']['list']
		));

		/* directory */

		if (is_dir($directory))
		{
			/* remove as needed */

			/* TODO: call _removeThumb if $directory is empty */
			if ($optionArray['command'] === 'remove')
			{
				self::_removeThumb($directory);
			}

			/* create as needed */

			/* TODO: call _createThumb if thumbs folder does not exist */
			if ($optionArray['command'] === 'create')
			{
				self::_createThumb($directory);
			}

			$outputItem .= self::_renderItem($directory, $optionArray);

			/* collect list output */

			if ($outputItem)
			{
				$output = $listElement->html($outputItem);
			}
		}

		/* else handle notification */

		else
		{
			self::setNotification('error', Language::get('directory_not_found') . Language::get('colon') . ' ' . $directory . Language::get('point'));
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

	public static function _renderItem($directory = null, $optionArray = array())
	{
		$outputItem = null;

		/* html elements */

		$imageElement = new Html\Element();
		$imageElement->init('img', array(
			'class' => self::$_configArray['className']['image']
		));
		$linkElement = new Html\Element();
		$linkElement->init('a');

		/* gallery directory */

		$galleryDirectory = new Directory();
		$galleryDirectory->init($directory, self::$_configArray['thumbDirectory']);
		$galleryDirectoryArray = $galleryDirectory->getArray();

		/* adjust order */

		if ($optionArray['order'] === 'desc')
		{
			$galleryDirectoryArray = array_reverse($galleryDirectoryArray);
		}

		/* gallery data */

		$galleryCounter = 0;
		$galleryTotal = count($galleryDirectoryArray);
		$galleryId = uniqid('gallery-');

		/* process directory */

		foreach ($galleryDirectoryArray as $value)
		{
			$imagePath = $directory . '/' . $value;
			$thumbPath = $directory . '/' . self::$_configArray['thumbDirectory'] . '/' . $value;

			/* get image data */

			$imageData = self::_getExifData($imagePath);

			/* collect item output */

			$outputItem .= '<li>';
			$outputItem .= $linkElement
				->copy()
				->attr(array(
					'href' => $imagePath,
					'data-counter' => ++$galleryCounter,
					'data-total' => $galleryTotal,
					'data-id' => $galleryId,
					'data-artist' => array_key_exists('artist', $imageData) ? $imageData['artist'] : null,
					'data-date' => array_key_exists('date', $imageData) ? $imageData['date'] : null,
					'data-description' => array_key_exists('description', $imageData) ? $imageData['description'] : null
				))
				->html(
					$imageElement
						->copy()
						->attr(array(
							'src' => $thumbPath,
							'alt' => array_key_exists('description', $imageData) ? $imageData['description'] : $value
						))
				);
			$outputItem .= '</li>';
		}
		return $outputItem;
	}

	/**
	 * getExifData
	 *
	 * @since 3.0.0
	 *
	 * @param string $file
	 *
	 * @return array
	 */

	protected static function _getExifData($file = null)
	{
		$dataArray = array();
		$exifArray = function_exists('exif_read_data') ? exif_read_data($file) : array();

		/* handle data */

		if ($exifArray)
		{
			$dataArray['artist'] = $exifArray['Artist'];
			$dataArray['date'] = $exifArray['DateTime'] ? date(Db::getSetting('date'), strtotime($exifArray['DateTime'])) : null;
			$dataArray['description'] = $exifArray['ImageDescription'];
		}
		return $dataArray;
	}

	/**
	 * removeThumb
	 *
	 * @since 3.0.0
	 *
	 * @param string $directory
	 */

	protected static function _removeThumb($directory = null)
	{
		$galleryDirectory = new Directory();
		$galleryDirectory->init($directory);
		$galleryDirectory->remove(self::$_configArray['thumbDirectory']);
	}

	/**
	 * createThumb
	 *
	 * @since 3.0.0
	 *
	 * @param string $directory
	 * @param array $optionArray
	 *
	 * @return string
	 */

	protected static function _createThumb($directory = null, $optionArray = array())
	{
		/* gallery directory */

		$galleryDirectory = new Directory();
		$galleryDirectory->init($directory, self::$_configArray['thumbDirectory']);
		$galleryDirectory->create(self::$_configArray['thumbDirectory']);
		$galleryDirectoryArray = $galleryDirectory->getArray();

		/* process directory */

		foreach ($galleryDirectoryArray as $value)
		{
			$imagePath = $directory . '/' . $value;
			$thumbPath = $directory . '/' . self::$_configArray['thumbDirectory'] . '/' . $value;
			$extension = strtolower(pathinfo($value, PATHINFO_EXTENSION));

			/* switch extension */

			switch ($extension)
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

			$sourceArray = self::_calcSource();
			/* TODO: height and width in distArray are empty */
			$distArray = self::_calcDist($sourceArray, $optionArray);

			/* create thumb files */

			$thumb = imagecreatetruecolor($distArray['width'], $distArray['height']);
			imagecopyresampled($thumb, $image, 0, 0, 0, 0, $distArray['width'], $distArray['height'], $sourceArray['width'], $sourceArray['height']);
			imagejpeg($thumb, $thumbPath, $distArray['quality']);
			/* TODO: add a notification if the file could not be created */

			/* destroy image */

			imagedestroy($thumb);
			imagedestroy($image);
		}
	}

	/**
	 * calcSource
	 *
	 * @param string $imagePath
	 *
	 * @return array
	 */

	protected static function _calcSource($imagePath = null)
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

	protected static function _calcDist($sourceArray = array(), $optionArray = array())
	{
		$distArray['height'] = array_key_exists('height', $optionArray) ? $optionArray['height'] : self::$_configArray['height'];
		$distArray['quality'] = array_key_exists('quality', $optionArray) ? $optionArray['quality'] : self::$_configArray['quality'];

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
