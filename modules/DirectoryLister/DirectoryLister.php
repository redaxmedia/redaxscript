<?php
namespace Redaxscript\Modules\DirectoryLister;

use Redaxscript\Dater;
use Redaxscript\Filesystem;
use Redaxscript\Filter;
use Redaxscript\Head;
use Redaxscript\Html;
use Redaxscript\Module;
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
use function urldecode;

/**
 * browse files of the directory
 *
 * @since 2.6.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class DirectoryLister extends Module\Metadata
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
		'description' => 'Browse files of the directory',
		'version' => '4.3.2'
	];

	/**
	 * array of the option
	 *
	 * @var array
	 */

	protected $_optionArray =
	[
		'className' =>
		[
			'list' => 'rs-list-directory-lister',
			'link' => 'rs-link-directory-lister',
			'textSize' => 'rs-text-directory-lister rs-is-size',
			'textDate' => 'rs-text-directory-lister rs-is-date',
			'types' =>
			[
				'directory' => 'rs-is-directory',
				'directoryParent' => 'rs-is-directory rs-is-parent',
				'file' => 'rs-is-file'
			]
		],
		'size' =>
		[
			'unit' => 'kB',
			'divider' => 1024
		],
		'extension' =>
		[
			'doc' => 'text',
			'txt' => 'text',
			'gif' => 'image',
			'jpg' => 'image',
			'pdf' => 'image',
			'png' => 'image',
			'mp3' => 'music',
			'wav' => 'music',
			'avi' => 'video',
			'mov' => 'video',
			'mp4' => 'video',
			'tar' => 'archive',
			'rar' => 'archive',
			'zip' => 'archive'
		]
	];

	/**
	 * renderStart
	 *
	 * @since 3.0.0
	 */

	public function renderStart() : void
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
	 * @return array
	 */

	public function adminNotification() : array
	{
		return $this->getNotificationArray();
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
			'class' => $this->_optionArray['className']['list']
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
		$queryString = $rootDirectory !== $parentDirectory ? '&' . urldecode(http_build_query(
		[
			'directory' => $parentDirectory
		])) : null;

		/* html element */

		$element = new Html\Element();
		$itemElement = $element->copy()->init('li');
		$linkElement = $element
			->copy()
			->init('a',
			[
				'class' => $this->_optionArray['className']['link']
			]);

		/* collect item output */

		$outputItem = $itemElement
			->html(
				$linkElement
				->attr(
				[
					'href' => $this->_registry->get('parameterRoute') . $this->_registry->get('fullRoute') . $queryString . $optionArray['hash'],
					'title' => $this->_language->get('_directory_lister')['directory_parent']
				])
				->addClass($this->_optionArray['className']['types']['directoryParent'])
				->text($this->_language->get('_directory_lister')['directory_parent'])
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
				'class' => $this->_optionArray['className']['link']
			]);
		$textSizeElement = $element
			->copy()
			->init('span',
			[
				'class' => $this->_optionArray['className']['textSize']
			]);
		$textDateElement = $element
			->copy()
			->init('span',
			[
				'class' => $this->_optionArray['className']['textDate']
			]);

		/* lister filesystem */

		$listerFilesystem = new Filesystem\Filesystem();
		$listerFilesystem->init($directory);
		$listerFilesystemArray = $listerFilesystem->getSortArray();

		/* process filesystem */

		foreach ($listerFilesystemArray as $value)
		{
			$path = $directory . DIRECTORY_SEPARATOR . $value;
			$fileName = pathinfo($path, PATHINFO_FILENAME);
			$fileExtension = pathinfo($path, PATHINFO_EXTENSION);
			$isDir = is_dir($path);
			$isFile = is_file($path) && is_array($this->_optionArray['extension']) && array_key_exists($fileExtension, $this->_optionArray['extension']);
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
								'href' => $this->_registry->get('parameterRoute') . $this->_registry->get('fullRoute') . '&' . urldecode(http_build_query(
								[
									'directory' => $path . $optionArray['hash']
								])),
								'title' => $this->_language->get('_directory_lister')['directory']
							])
							->addClass($this->_optionArray['className']['types']['directory'])
							->text($value)
					)
					->append($textSizeElement);
			}

			/* else handle file */

			else if ($isFile)
			{
				$fileType = $this->_optionArray['extension'][$fileExtension];
				$textSize = ceil(filesize($path) / $this->_optionArray['size']['divider']);
				$itemElement
					->clear()
					->html(
						$linkElement
							->copy()
							->attr(
							[
								'href' => $this->_registry->get('root') . '/' . $path,
								'target' => '_blank',
								'title' => $this->_language->get('_directory_lister')['file'],
								'data-file-name' => $fileName,
								'data-file-extension' => $fileExtension,
								'data-file-type' => $fileType
							])
							->addClass($this->_optionArray['className']['types']['file'])
							->text($value)
					)
					->append(
						$textSizeElement
							->copy()
							->attr('data-unit', $this->_optionArray['size']['unit'])
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
}
