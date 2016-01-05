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
		$loader_modules_styles[] = 'modules/Gallery/assets/styles/query.css';
		$loader_modules_scripts[] = 'modules/Gallery/assets/scripts/init.js';
		$loader_modules_scripts[] = 'modules/Gallery/assets/scripts/gallery.js';
	}

	/**
	 * render
	 *
	 * @since 2.6.0
	 *
	 * @param string $directory
	 * @param array $options
	 *
	 * @return string
	 */

	public static function render($directory = null, $options = null)
	{
		$output = null;
		$outputItem = null;

		/* html elements */

		$imageElement = new Html\Element();
		$imageElement->init('img', array(
			'class' => self::$_config['className']['image']
		));
		$linkElement = new Html\Element();
		$linkElement->init('a');
		$listElement = new Html\Element();
		$listElement->init('ul', array(
			'class' => self::$_config['className']['list']
		));

		/* gallery directory */

		$galleryDirectory = new Directory();
		$galleryDirectory->init($directory, self::$_config['thumbDirectory']);
		$galleryDirectoryArray = $galleryDirectory->getArray();

		/* adjust order */

		if ($options['order'] === 'desc')
		{
			$galleryDirectoryArray = array_reverse($galleryDirectoryArray);
		}

		/* gallery data */

		$galleryCounter = 0;
		$galleryTotal = count($galleryDirectoryArray);
		$galleryId = uniqid('gallery-');

		/* remove thumbs */

		if ($options['command'] === 'remove' || !$galleryTotal)
		{
			$galleryDirectory->remove(self::$_config['thumbDirectory']);
		}

		/* else handle thumbs */

		else
		{
			/* process directory */

			foreach ($galleryDirectoryArray as $key => $value)
			{
				$imagePath = $directory . '/' . $value;
				$thumbPath = $directory . '/' . self::$_config['thumbDirectory'] . '/' . $value;

				/* create thumbs */

				if ($options['command'] === 'create' || !is_file($thumbPath))
				{
					self::_createThumb($value, $directory, $options);
				}

				/* image data */

				$imageData = self::_getImageData($imagePath);

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
	 * getImageData
	 *
	 * @since 2.6.0
	 *
	 * @param string $file
	 *
	 * @return array
	 */

	protected static function _getImageData($file = null)
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
			$output['date'] = $exifData['DateTime'] ? date(Db::getSettings('data'), strtotime($exifData['DateTime'])) : null;
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
	 * @param array $options
	 *
	 * @return string
	 */

	protected static function _createThumb($file = null, $directory = null, $options = null)
	{
		/* options fallback */

		$options['height'] = array_key_exists('height', $options) ? $options['height'] : self::$_config['height'];
		$options['quality'] = array_key_exists('quality', $options) ? $options['quality'] : self::$_config['quality'];

		/* get extension */

		$imagePath = $directory . '/' . $file;
		$extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));

		/* generate image */

		switch ($extension)
		{
			case 'gif':
				$image = imagecreatefromgif($imagePath);
			case 'jpg':
				$image = imagecreatefromjpeg($imagePath);
				break;
			case 'png':
				$image = imagecreatefrompng($imagePath);
				break;
		}

		/* original image */

		$original['dimensions'] = getimagesize($imagePath);
		$original['height'] = $original['dimensions'][1];
		$original['width'] = $original['dimensions'][0];

		/* calculate dimensions */

		if ($options['height'])
		{
			$options['scaling'] = $options['height'] / $original['height'] * 100;
		}
		else
		{
			$options['height'] = round($options['scaling'] / 100 * $original['height']);
		}
		$options['width'] = round($options['scaling'] / 100 * $original['width']);

		/* create thumb directory */

		$thumbDirectory = $directory . '/' . self::$_config['thumbDirectory'];

		if (!is_dir($thumbDirectory))
		{
			mkdir($thumbDirectory, 0755);
		}

		/* create thumb */

		$thumb = imagecreatetruecolor($options['width'], $options['height']);
		imagecopyresampled($thumb, $image, 0, 0, 0, 0, $options['width'], $options['height'], $original['width'], $original['height']);
		imagejpeg($thumb, $thumbDirectory . '/' . $file, $options['quality']);

		/* destroy images */

		imagedestroy($thumb);
		imagedestroy($image);
	}
}
