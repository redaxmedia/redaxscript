<?php
namespace Redaxscript\Modules\DirectoryLister;

use Redaxscript\Db;
use Redaxscript\Directory;
use Redaxscript\Filter;
use Redaxscript\Head;
use Redaxscript\Html;

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
			->appendFile('modules/DirectoryLister/dist/styles/directory-lister.css');
	}

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
	 * render
	 *
	 * @since 2.6.0
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

		/* handle option */

		if ($optionArray['hash'])
		{
			$optionArray['hash'] = '#' . $optionArray['hash'];
		}

		/* handle query */

		$directoryQuery = $this->_request->getQuery('directory');
		$directoryQueryArray = explode('/', $directoryQuery);

		/* parent directory */

		if ($directoryQueryArray[0] === $directory && $directory !== $directoryQuery)
		{
			$pathFilter = new Filter\Path();
			$rootDirectory = $directory;
			$directory = $pathFilter->sanitize($directoryQuery);
			$parentDirectory = $pathFilter->sanitize(dirname($directory));
			$outputItem .= $this->_renderParent($rootDirectory, $parentDirectory, $optionArray);
		}

		/* has directory */

		if (is_dir($directory))
		{
			$outputItem .= $this->_renderItem($directory, $optionArray);

			/* collect list output */

			if ($outputItem)
			{
				$output = $listElement->html($outputItem);
			}
		}

		/* else handle notification */

		else
		{
			$this->setNotification('error', $this->_language->get('directory_not_found') . $this->_language->get('colon') . ' ' . $directory . $this->_language->get('point'));
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

	protected function _renderParent($rootDirectory = null, $parentDirectory = null, $optionArray = [])
	{
		$outputItem = null;
		$queryString = $rootDirectory !== $parentDirectory ? '&directory=' . $parentDirectory : null;

		/* html elements */

		$linkElement = new Html\Element();
		$linkElement->init('a',
		[
			'class' => $this->_configArray['className']['link']
		]);

		/* collect item output */

		$outputItem .= '<li>';
		$outputItem .= $linkElement
			->attr(
			[
				'href' => $this->_registry->get('parameterRoute') . $this->_registry->get('fullRoute') . $queryString . $optionArray['hash'],
				'title' => $this->_language->get('directory_parent', '_directory_lister')
			])
			->addClass($this->_configArray['className']['types']['directoryParent'])
			->text($this->_language->get('directory_parent', '_directory_lister'));
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

	protected function _renderItem($directory = null, $optionArray = [])
	{
		$outputItem = null;

		/* html elements */

		$linkElement = new Html\Element();
		$linkElement->init('a',
		[
			'class' => $this->_configArray['className']['link']
		]);
		$textSizeElement = new Html\Element();
		$textSizeElement->init('span',
		[
			'class' => $this->_configArray['className']['textSize']
		]);
		$textDateElement = new Html\Element();
		$textDateElement->init('span',
		[
			'class' => $this->_configArray['className']['textDate']
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
			$text = $this->_replace($value, $fileExtension, $optionArray['replace']);
			$textDate = date(Db::getSetting('date'), filectime($path));
			$isDir = is_dir($path);
			$isFile = is_file($path) && array_key_exists($fileExtension, $this->_configArray['extension']);

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
						'href' => $this->_registry->get('parameterRoute') . $this->_registry->get('fullRoute') . '&directory=' . $path . $optionArray['hash'],
						'title' => $this->_language->get('directory', '_directory_lister')
					])
					->addClass($this->_configArray['className']['types']['directory'])
					->text($text);
				$outputItem .= $textSizeElement->copy();
			}

			/* else handle file */

			else if ($isFile)
			{
				$fileType = $this->_configArray['extension'][$fileExtension];
				$textSize = ceil(filesize($path) / $this->_configArray['size']['divider']);
				$outputItem .= $linkElement
					->copy()
					->attr(
					[
						'href' => $this->_registry->get('root') . '/' . $path,
						'target' => '_blank',
						'title' => $this->_language->get('file', '_directory_lister')
					])
					->addClass($this->_configArray['className']['types'][$fileType])
					->text($text);
				$outputItem .= $textSizeElement
					->copy()
					->attr('data-unit', $this->_configArray['size']['unit'])
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

	protected function _replace($text, $fileExtension, $replaceArray)
	{
		foreach ($replaceArray as $replaceKey => $replaceValue)
		{
			if ($replaceKey === $this->_configArray['replaceKey']['extension'])
			{
				$replaceKey = $fileExtension;
			}
			$text = str_replace($replaceKey, $replaceValue, $text);
		}
		return $text;
	}
}
