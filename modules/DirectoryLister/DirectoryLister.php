<?php
namespace Redaxscript\Modules\DirectoryLister;

use Redaxscript\Db;
use Redaxscript\Directory;
use Redaxscript\Filter;
use Redaxscript\Html;
use Redaxscript\Language;
use Redaxscript\Registry;
use Redaxscript\Request;

/**
 * simple directory lister
 *
 * @since 2.6.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class DirectoryLister extends Config
{
	/**
	 * array of the module
	 *
	 * @var array
	 */

	protected static $_moduleArray = array(
		'name' => 'Directory lister',
		'alias' => 'DirectoryLister',
		'author' => 'Redaxmedia',
		'description' => 'Simple directory lister',
		'version' => '3.0.0'
	);

	/**
	 * loaderStart
	 *
	 * @since 2.6.0
	 */

	public static function loaderStart()
	{
		global $loader_modules_styles;
		$loader_modules_styles[] = 'modules/DirectoryLister/assets/styles/directory_lister.css';
	}

	/**
	 * adminPanelNotification
	 *
	 * @since 3.0.0
	 */

	public static function adminPanelNotification()
	{
		return self::getNotification();
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

		$linkElement = new Html\Element();
		$linkElement->init('a', array(
			'class' => self::$_configArray['className']['link']
		));
		$textSizeElement = new Html\Element();
		$textSizeElement->init('span', array(
			'class' => self::$_configArray['className']['textSize']
		));
		$textDateElement = new Html\Element();
		$textDateElement->init('span', array(
			'class' => self::$_configArray['className']['textDate']
		));
		$listElement = new Html\Element();
		$listElement->init('ul', array(
			'class' => self::$_configArray['className']['list']
		));

		/* hash option */

		if ($optionArray['hash'])
		{
			$hashString = '#' . $optionArray['hash'];
		}

		/* handle query */

		$directoryQuery = Request::getQuery('d');
		if ($directoryQuery && $directory !== $directoryQuery)
		{
			$pathFilter = new Filter\Path();
			$directory = $pathFilter->sanitize($directoryQuery);
			$parentDirectory = $pathFilter->sanitize(dirname($directory));
		}

		/* directory */

		if (is_dir($directory))
		{
			/* lister directory */

			$listerDirectory = new Directory();
			$listerDirectory->init($directory);
			$listerDirectoryArray = $listerDirectory->getArray();

			/* date format */

			$dateFormat = Db::getSetting('date');

			/* parent directory */

			if (is_dir($parentDirectory))
			{
				$outputItem .= '<li>';
				$outputItem .= $linkElement
					->copy()
					->attr(array(
						'href' => Registry::get('parameterRoute') . Registry::get('fullRoute') . '&d=' . $parentDirectory . $hashString,
						'title' => Language::get('directory_parent', '_directory_lister')
					))
					->addClass(self::$_configArray['className']['types']['directoryParent'])
					->text(Language::get('directory_parent', '_directory_lister'));
				$outputItem .= '</li>';
			}

			/* process directory */

			foreach ($listerDirectoryArray as $key => $value)
			{
				$path = $directory . '/' . $value;
				$fileExtension = pathinfo($path, PATHINFO_EXTENSION);
				$text = self::_replace($value, $fileExtension, $optionArray['replace']);
				$isDir = is_dir($path);
				$isFile = is_file($path) && array_key_exists($fileExtension, self::$_configArray['extension']);

				/* handle directory */

				if ($isDir || $isFile)
				{
					$outputItem .= '<li>';
				}
				if ($isDir)
				{
					$outputItem .= $linkElement
						->copy()
						->attr(array(
							'href' => Registry::get('parameterRoute') . Registry::get('fullRoute') . '&d=' . $path . $hashString,
							'title' => Language::get('directory', '_directory_lister')
						))
						->addClass(self::$_configArray['className']['types']['directory'])
						->text($text);
					$outputItem .= $textSizeElement->copy();
				}

				/* else handle file */

				else if ($isFile)
				{
					$fileType = self::$_configArray['extension'][$fileExtension];
					$outputItem .= $linkElement
						->copy()
						->attr(array(
							'href' => Registry::get('root') . '/' . $path,
							'target' => '_blank',
							'title' => Language::get('file', '_directory_lister')
						))
						->addClass(self::$_configArray['className']['types'][$fileType])
						->text($text);
					$outputItem .= $textSizeElement
						->copy()
						->attr('data-unit', self::$_configArray['size']['unit'])
						->html(ceil(filesize($path) / self::$_configArray['size']['divider']));
				}
				if ($isDir || $isFile)
				{
					$outputItem .= $textDateElement
						->copy()
						->html(date($dateFormat, filectime($path)));
					$outputItem .= '</li>';
				}
			}

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
	 * @param string $text
	 * @param string $fileExtension
	 * @param array $replaceArray
	 *
	 * @return string
	 */

	protected static function _replace($text, $fileExtension, $replaceArray)
	{
		foreach ($replaceArray as $replaceKey => $replaceValue)
		{
			if ($replaceKey === self::$_configArray['replaceKey']['extension'])
			{
				$replaceKey = $fileExtension;
			}
			$text = str_replace($replaceKey, $replaceValue, $text);
		}
		return $text;
	}
}
