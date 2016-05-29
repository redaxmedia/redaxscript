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
	 * render
	 *
	 * @since 2.6.0
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

		$imageElement = new Html\Element();
		$imageElement->init('img', array(
			'class' => self::$_configArray['className']['image']
		));
		$linkElement = new Html\Element();
		$linkElement->init('a');
		$listElement = new Html\Element();
		$listElement->init('ul', array(
			'class' => self::$_configArray['className']['list']
		));

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

		/* remove thumbs */

		if (!$galleryTotal || $optionArray['command'] === 'remove')
		{
			$galleryDirectory->remove(self::$_configArray['thumbDirectory']);
		}

		/* else handle thumbs */

		else
		{
			/* process directory */

			foreach ($galleryDirectoryArray as $key => $value)
			{
				$imagePath = $directory . '/' . $value;
				$thumbPath = $directory . '/' . self::$_configArray['thumbDirectory'] . '/' . $value;

				/* create thumbs */

				if ($optionArray['command'] === 'create' || !is_file($thumbPath))
				{
					self::_createThumb($value, $directory, $optionArray);
				}

				/* image data */

				$imageData = self::_imageData($imagePath);

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
								'alt' => array_key_exists('description', $imageData) ? $imageData['description'] : null
							))
					);
				$outputItem .= '</li>';
			}
			$output = $listElement->attr('id', $galleryId)->html($outputItem);
		}
		return $output;
	}

	/**
	 * imageData
	 *
	 * @since 2.6.0
	 *
	 * @param string $file
	 *
	 * @return array
	 */

	protected static function _imageData($file = null)
	{
		$output = array();
		if (function_exists('exif_read_data'))
		{
			$exifData = exif_read_data($file);
		}

		/* has image data */

		if ($exifData)
		{
			$output['artist'] = $exifData['Artist'];
			$output['date'] = $exifData['DateTime'] ? date(Db::getSetting('date'), strtotime($exifData['DateTime'])) : null;
			$output['description'] = $exifData['ImageDescription'];
		}
		return $output;
	}

	/**
	 * createThumb
	 *
	 * @since 2.6.0
	 *
	 * @param string $file
	 * @param string $directory
	 * @param array $optionArray
	 *
	 * @return string
	 */

	protected static function _createThumb($file = null, $directory = null, $optionArray = array())
	{
		/* options fallback */

		$optionArray['height'] = array_key_exists('height', $optionArray) ? $optionArray['height'] : self::$_configArray['height'];
		$optionArray['quality'] = array_key_exists('quality', $optionArray) ? $optionArray['quality'] : self::$_configArray['quality'];

		/* get extension */

		$imagePath = $directory . '/' . $file;
		$extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));

		/* generate image */

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
		}

		/* original image */

		$originalArray['dimensions'] = getimagesize($imagePath);
		$originalArray['height'] = $originalArray['dimensions'][1];
		$originalArray['width'] = $originalArray['dimensions'][0];

		/* calculate dimensions */

		if ($optionArray['height'])
		{
			$optionArray['scaling'] = $optionArray['height'] / $originalArray['height'] * 100;
		}
		else
		{
			$optionArray['height'] = round($optionArray['scaling'] / 100 * $originalArray['height']);
		}
		$optionArray['width'] = round($optionArray['scaling'] / 100 * $originalArray['width']);

		/* create thumb directory */

		$thumbDirectory = new Directory();
		$thumbDirectory->init($directory);
		$thumbDirectory->create(self::$_configArray['thumbDirectory']);

		/* create thumb */

		$thumb = imagecreatetruecolor($optionArray['width'], $optionArray['height']);
		imagecopyresampled($thumb, $image, 0, 0, 0, 0, $optionArray['width'], $optionArray['height'], $originalArray['width'], $originalArray['height']);
		imagejpeg($thumb, $thumbDirectory, $optionArray['quality']);

		/* destroy images */

		imagedestroy($thumb);
		imagedestroy($image);
	}
}
