<?php
namespace Redaxscript\Modules\Gallery;

use Redaxscript\Db;
use Redaxscript\Directory;
use Redaxscript\Head;
use Redaxscript\Html;
use Redaxscript\Language;

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

	protected static $_moduleArray =
	[
		'name' => 'Gallery',
		'alias' => 'Gallery',
		'author' => 'Redaxmedia',
		'description' => 'Lightbox enhanced image gallery',
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

	public static function renderStart()
	{
		/* link */

		$link = Head\Link::getInstance();
		$link
			->init()
			->appendFile('modules/Gallery/assets/styles/gallery.css');

		/* script */

		$script = Head\Script::getInstance();
		$script
			->init('foot')
			->appendFile('modules/Gallery/assets/scripts/init.js')
			->appendFile('modules/Gallery/assets/scripts/gallery.js');
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

	public static function render($directory = null, $optionArray = [])
	{
		$output = null;
		$outputItem = null;

		/* html elements */

		$listElement = new Html\Element();
		$listElement->init('ul',
		[
			'class' => self::$_configArray['className']['list']
		]);

		/* has directory */

		if (is_dir($directory))
		{
			/* remove as needed */

			if ($optionArray['command'] === 'remove')
			{
				self::_removeThumb($directory);
			}

			/* create as needed */

			if ($optionArray['command'] === 'create' || !is_dir($directory . '/' . self::$_configArray['thumbDirectory']))
			{
				self::_createThumb($directory, $optionArray);
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

	public static function _renderItem($directory = null, $optionArray = [])
	{
		$outputItem = null;

		/* html elements */

		$imageElement = new Html\Element();
		$imageElement->init('img',
		[
			'class' => self::$_configArray['className']['image']
		]);
		$linkElement = new Html\Element();
		$linkElement->init('a');

		/* gallery directory */

		$galleryDirectory = new Directory();
		$galleryDirectory->init($directory,
		[
			self::$_configArray['thumbDirectory']
		]);
		$galleryDirectoryArray = $galleryDirectory->getArray();

		/* adjust order */

		if ($optionArray['order'] === 'desc')
		{
			$galleryDirectoryArray = array_reverse($galleryDirectoryArray);
		}

		/* gallery data */

		$galleryCounter = 0;
		$galleryTotal = count($galleryDirectoryArray);

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
				->attr(
				[
					'href' => $imagePath,
					'data-counter' => ++$galleryCounter,
					'data-total' => $galleryTotal,
					'data-artist' => array_key_exists('artist', $imageData) ? $imageData['artist'] : null,
					'data-date' => array_key_exists('date', $imageData) ? $imageData['date'] : null,
					'data-description' => array_key_exists('description', $imageData) ? $imageData['description'] : null
				])
				->html(
					$imageElement
						->copy()
						->attr(
						[
							'src' => $thumbPath,
							'alt' => array_key_exists('description', $imageData) ? $imageData['description'] : $value
						])
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
		$dataArray = [];
		$exifArray = function_exists('exif_read_data') ? exif_read_data($file) : [];

		/* handle data */

		if ($exifArray)
		{
			$dataArray['artist'] = $exifArray['Artist'];
			$dataArray['date'] = $exifArray['DateTime'] ? date(Db::getSetting('date'), strtotime($exifArray['DateTime'])) : null;
			$dataArray['description'] = $exifArray['ImageDescription'];
		}
		$dataArray = array_filter($dataArray);
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

	protected static function _createThumb($directory = null, $optionArray = [])
	{
		/* gallery directory */

		$galleryDirectory = new Directory();
		$galleryDirectory->init($directory,
		[
			self::$_configArray['thumbDirectory']
		]);
		$galleryDirectory->create(self::$_configArray['thumbDirectory']);
		$galleryDirectoryArray = $galleryDirectory->getArray();

		/* process directory */

		if (chmod($directory . '/' . self::$_configArray['thumbDirectory'], 0777))
		{
			foreach ($galleryDirectoryArray as $value)
			{
				$imagePath = $directory . '/' . $value;
				$imageExtension = strtolower(pathinfo($value, PATHINFO_EXTENSION));
				$thumbPath = $directory . '/' . self::$_configArray['thumbDirectory'] . '/' . $value;

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

				$sourceArray = self::_calcSource($imagePath);
				$distArray = self::_calcDist($sourceArray, $optionArray);

				/* create thumb files */

				$thumb = imagecreatetruecolor($distArray['width'], $distArray['height']);
				imagecopyresampled($thumb, $image, 0, 0, 0, 0, $distArray['width'], $distArray['height'], $sourceArray['width'], $sourceArray['height']);
				imagejpeg($thumb, $thumbPath, $distArray['quality']);

				/* destroy image */

				imagedestroy($thumb);
				imagedestroy($image);
			}
		}

		/* handle notification */

		else
		{
			self::setNotification('error', Language::get('directory_permission_grant') . Language::get('colon') . ' ' . $directory . '/' . self::$_configArray['thumbDirectory']  . Language::get('point'));
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

	protected static function _calcDist($sourceArray = [], $optionArray = [])
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
