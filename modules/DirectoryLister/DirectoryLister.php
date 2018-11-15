<?php
namespace Redaxscript\Modules\DirectoryLister;

use Redaxscript\Dater;
use Redaxscript\Filesystem;
use Redaxscript\Filter;
use Redaxscript\Head;
use Redaxscript\Html;
use function array_key_exists;
use function ceil;
use function dirname;
use function explode;
use function filectime;
use function filesize;
use function http_build_query;
use function is_array;
use function is_dir;
use function is_file;
use function pathinfo;
use function str_replace;

/**
 * list the files of a directory
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
		'name' => 'Directory Lister',
		'alias' => 'DirectoryLister',
		'author' => 'Redaxmedia',
		'description' => 'List the files of a directory',
		'version' => '4.0.0'
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
			->appendFile('modules/DirectoryLister/dist/styles/directory-lister.min.css');
	}

	/**
	 * adminNotification
	 *
	 * @since 3.0.0
	 *
	 * @return array|null
	 */

	public function adminNotification() : ?array
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
	 * @return string|null
	 */

	public function render(string $directory = null, array $optionArray = []) : ?string
	{
		$output = null;
		$outputItem = null;

		/* html element */

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

		/* handle notification */

		if (!is_dir($directory))
		{
			$this->setNotification('error', $this->_language->get('directory_not_found') . $this->_language->get('colon') . ' ' . $directory . $this->_language->get('point'));
		}

		/* else collect item */

		else
		{
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
	 * renderParent
	 *
	 * @param string $rootDirectory
	 * @param string $parentDirectory
	 * @param array $optionArray
	 *
	 * @return string|null
	 */

	protected function _renderParent(string $rootDirectory = null, string $parentDirectory = null, array $optionArray = []) : ?string
	{
		$queryString = $rootDirectory !== $parentDirectory ? '&' . http_build_query(
		[
			'directory' => $parentDirectory
		]) : null;

		/* html element */

		$element = new Html\Element();
		$itemElement = $element->copy()->init('li');
		$linkElement = $element
			->copy()
			->init('a',
			[
				'class' => $this->_configArray['className']['link']
			]);

		/* collect item output */

		$outputItem = $itemElement
			->html(
				$linkElement
				->attr(
				[
					'href' => $this->_registry->get('parameterRoute') . $this->_registry->get('fullRoute') . $queryString . $optionArray['hash'],
					'title' => $this->_language->get('directory_parent', '_directory_lister')
				])
				->addClass($this->_configArray['className']['types']['directoryParent'])
				->text($this->_language->get('directory_parent', '_directory_lister'))
			);
		return $outputItem;
	}

	/**
	 * renderItem
	 *
	 * @param string $directory
	 * @param array $optionArray
	 *
	 * @return string|null
	 */

	protected function _renderItem(string $directory = null, array $optionArray = []) : ?string
	{
		$outputItem = null;
		$dater = new Dater();

		/* html element */

		$element = new Html\Element();
		$itemElement = $element->copy()->init('li');
		$linkElement = $element
			->copy()
			->init('a',
			[
				'class' => $this->_configArray['className']['link']
			]);
		$textSizeElement = $element
			->copy()
			->init('span',
			[
				'class' => $this->_configArray['className']['textSize']
			]);
		$textDateElement = $element
			->copy()
			->init('span',
			[
				'class' => $this->_configArray['className']['textDate']
			]);

		/* lister filesystem */

		$listerFilesystem = new Filesystem\Filesystem();
		$listerFilesystem->init($directory);
		$listerFilesystemArray = $listerFilesystem->getSortArray();

		/* process filesystem */

		foreach ($listerFilesystemArray as $value)
		{
			$path = $directory . DIRECTORY_SEPARATOR . $value;
			$fileExtension = pathinfo($path, PATHINFO_EXTENSION);
			$text = $this->_replace($value, $fileExtension, $optionArray['replace']);
			$isDir = is_dir($path);
			$isFile = is_file($path) && is_array($this->_configArray['extension']) && array_key_exists($fileExtension, $this->_configArray['extension']);
			$dater->init(filectime($path));

			/* handle directory */

			if ($isDir)
			{
				$itemElement
					->clear()
					->html(
						$linkElement
							->copy()
							->attr(
							[
								'href' => $this->_registry->get('parameterRoute') . $this->_registry->get('fullRoute') . '&' . http_build_query(
								[
									'directory' => $path . $optionArray['hash']
								]),
								'title' => $this->_language->get('directory', '_directory_lister')
							])
							->addClass($this->_configArray['className']['types']['directory'])
							->text($text)
					)
					->append($textSizeElement);
			}

			/* else handle file */

			else if ($isFile)
			{
				$fileType = $this->_configArray['extension'][$fileExtension];
				$textSize = ceil(filesize($path) / $this->_configArray['size']['divider']);
				$itemElement
					->clear()
					->html(
						$linkElement
							->copy()
							->attr(
							[
								'href' => $this->_registry->get('root') . '/' . $path,
								'target' => '_blank',
								'title' => $this->_language->get('file', '_directory_lister')
							])
							->addClass($this->_configArray['className']['types'][$fileType])
							->text($text)
					)
					->append(
						$textSizeElement
							->copy()
							->attr('data-unit', $this->_configArray['size']['unit'])
							->text($textSize)
					);
			}
			if ($isDir || $isFile)
			{
				$outputItem .= $itemElement
					->append($textDateElement
						->copy()
						->text($dater->formatDate())
					);
			}
		}
		return $outputItem;
	}

	/**
	 * replace
	 *
	 * @param string $text
	 * @param string $fileExtension
	 * @param array|null $replaceArray
	 *
	 * @return string
	 */

	protected function _replace(string $text, string $fileExtension, ?array $replaceArray = []) : string
	{
		if (is_array($replaceArray))
		{
			foreach ($replaceArray as $replaceKey => $replaceValue)
			{
				if ($replaceKey === $this->_configArray['replaceKey']['extension'])
				{
					$replaceKey = $fileExtension;
				}
				$text = str_replace($replaceKey, $replaceValue, $text);
			}
		}
		return $text;
	}
}
