<?php
namespace Redaxscript\Modules\Gallery;

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
		'version' => '2.6.0'
	);

	/**
	 * loaderStart
	 *
	 * @since 2.6.0
	 */

	public static function loaderStart()
	{
		global $loader_modules_styles, $loader_modules_scripts;
		$loader_modules_styles[] = 'modules/Gallery/styles/gallery.css';
		$loader_modules_styles[] = 'modules/Gallery/styles/query.css';
		$loader_modules_scripts[] = 'modules/Gallery/scripts/init.js';
		$loader_modules_scripts[] = 'modules/Gallery/scripts/gallery.js';
	}

	/**
	 * render
	 *
	 * @since 2.6.0
	 *
	 * @param string $directory
	 * @param mixed $options
	 *
	 * @return string
	 */

	public static function render($directory = null, $options = null)
	{
		$output = '';
		$outputItem = '';

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

		/* options fallback */

		if (is_string($options) && in_array($options, self::$_config['allowedCommands']))
		{
			$options['command'] = $options;
		}

		/* gallery directory */

		$galleryDirectory = new Directory();
		$galleryDirectory->init($directory, self::$_config['thumbDirectory']);
		$galleryDirectoryArray = $galleryDirectory->getArray();

		/* remove thumbs */

		if ($options['command'] === 'remove')
		{
			$galleryDirectory->remove(self::$_config['thumbDirectory']);
		}

		/* else show thumbs */

		else
		{
			/* reverse order */

			if ($options['order'] === 'desc')
			{
				$galleryDirectoryArray = array_reverse($galleryDirectoryArray);
			}

			/* process directory */

			foreach ($galleryDirectoryArray as $key => $value)
			{
				$imagePath = $directory . '/' . $value;
				$thumbPath = $directory . '/' . self::$_config['thumbDirectory'] . '/' . $value;

				/* create thumb as needed */

				if ($options['command'] === 'create' || !is_dir($thumbPath))
				{
					self::_createThumb($value, $directory, $options);
				}

				/* collect item output */

				$outputItem .= '<li>';
				$outputItem .= $linkElement
					->copy()
					->attr('src', $imagePath)
					->html($imageElement->copy()->attr('src', $thumbPath));
				$outputItem .= '</li>';
			}
			$output = $listElement->html($outputItem);
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
	 * @param mixed $options
	 *
	 * @return string
	 */

	public static function _createThumb($file = null, $directory = null, $options = null)
	{
		/* options fallback */

		$options['height'] = array_key_exists('height') ? $options['height'] : self::$_config['height'];
		$options['quality'] = array_key_exists('quality') ? $options['quality'] : self::$_config['quality'];

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
