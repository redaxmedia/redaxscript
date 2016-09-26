<?php
namespace Redaxscript\Modules\DirectoryLister;

use Redaxscript\Db;
use Redaxscript\Directory;
use Redaxscript\Filter;
use Redaxscript\Head;
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

	protected static $_moduleArray =
	[
		'name' => 'Directory lister',
		'alias' => 'DirectoryLister',
		'author' => 'Redaxmedia',
		'description' => 'Simple directory lister',
		'version' => '3.0.0'
	];

	/**
	 * renderStart
	 *
	 * @since 3.0.0
	 */

	public function renderStart()
	{
		$link = Head\Link::getInstance();
		$link
			->init()
			->appendFile('modules/DirectoryLister/assets/styles/directory_lister.css');

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
	 * @since 2.6.0
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

		/* handle option */

		if ($optionArray['hash'])
		{
			$optionArray['hash'] = '#' . $optionArray['hash'];
		}

		/* handle query */

		$directoryQuery = Request::getQuery('d');
		$directoryQueryArray = explode('/', $directoryQuery);

		/* parent directory */

		if ($directoryQueryArray[0] === $directory && $directory !== $directoryQuery)
		{
			$pathFilter = new Filter\Path();
			$rootDirectory = $directory;
			$directory = $pathFilter->sanitize($directoryQuery);
			$parentDirectory = $pathFilter->sanitize(dirname($directory));
			$outputItem .= self::_renderParent($rootDirectory, $parentDirectory, $optionArray);
		}

		/* has directory */

		if (is_dir($directory))
		{
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
	 * renderParent
	 *
	 * @param string $rootDirectory
	 * @param string $parentDirectory
	 * @param array $optionArray
	 *
	 * @return string
	 */

	protected static function _renderParent($rootDirectory = null, $parentDirectory = null, $optionArray = [])
	{
		$outputItem = null;
		$queryString = $rootDirectory !== $parentDirectory ? '&d=' . $parentDirectory : null;

		/* html elements */

		$linkElement = new Html\Element();
		$linkElement->init('a',
		[
			'class' => self::$_configArray['className']['link']
		]);

		/* collect item output */

		$outputItem .= '<li>';
		$outputItem .= $linkElement
			->attr(
			[
				'href' => Registry::get('parameterRoute') . Registry::get('fullRoute') . $queryString. $optionArray['hash'],
				'title' => Language::get('directory_parent', '_directory_lister')
			])
			->addClass(self::$_configArray['className']['types']['directoryParent'])
			->text(Language::get('directory_parent', '_directory_lister'));
		$outputItem .= '</li>';
		return $outputItem;
	}

	/**
	 * renderItem
	 *
	 * @param string $directory
	 * @param array $optionArray
	 *
	 * @return string
	 */

	protected static function _renderItem($directory = null, $optionArray = [])
	{
		$outputItem = null;

		/* html elements */

		$linkElement = new Html\Element();
		$linkElement->init('a',
		[
			'class' => self::$_configArray['className']['link']
		]);
		$textSizeElement = new Html\Element();
		$textSizeElement->init('span',
		[
			'class' => self::$_configArray['className']['textSize']
		]);
		$textDateElement = new Html\Element();
		$textDateElement->init('span',
		[
			'class' => self::$_configArray['className']['textDate']
		]);

		/* lister directory */

		$listerDirectory = new Directory();
		$listerDirectory->init($directory);
		$listerDirectoryArray = $listerDirectory->getArray();

		/* process directory */

		foreach ($listerDirectoryArray as $value)
		{
			$path = $directory . '/' . $value;
			$fileExtension = pathinfo($path, PATHINFO_EXTENSION);
			$text = self::_replace($value, $fileExtension, $optionArray['replace']);
			$textDate = date(Db::getSetting('date'), filectime($path));
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
					->attr(
					[
						'href' => Registry::get('parameterRoute') . Registry::get('fullRoute') . '&d=' . $path . $optionArray['hash'],
						'title' => Language::get('directory', '_directory_lister')
					])
					->addClass(self::$_configArray['className']['types']['directory'])
					->text($text);
				$outputItem .= $textSizeElement->copy();
			}

			/* else handle file */

			else if ($isFile)
			{
				$fileType = self::$_configArray['extension'][$fileExtension];
				$textSize = ceil(filesize($path) / self::$_configArray['size']['divider']);
				$outputItem .= $linkElement
					->copy()
					->attr(
					[
						'href' => Registry::get('root') . '/' . $path,
						'target' => '_blank',
						'title' => Language::get('file', '_directory_lister')
					])
					->addClass(self::$_configArray['className']['types'][$fileType])
					->text($text);
				$outputItem .= $textSizeElement
					->copy()
					->attr('data-unit', self::$_configArray['size']['unit'])
					->text($textSize);
			}
			if ($isDir || $isFile)
			{
				$outputItem .= $textDateElement
					->copy()
					->text($textDate);
				$outputItem .= '</li>';
			}
		}
		return $outputItem;
	}

	/**
	 * replace
	 *
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
