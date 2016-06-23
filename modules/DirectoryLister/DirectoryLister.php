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
		$outputDirectory = null;
		$outputFile = null;

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

		/* has directory */

		if (is_dir($directory))
		{
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

			/* lister directory object */

			$listerDirectory = new Directory();
			$listerDirectory->init($directory);
			$listerDirectoryArray = $listerDirectory->getArray();

			/* date format */

			$dateFormat = Db::getSetting('date');

			/* parent directory */

			if (is_dir($parentDirectory))
			{
				$outputDirectory .= '<li>';
				$outputDirectory .= $linkElement
					->copy()
					->attr(array(
						'href' => Registry::get('parameterRoute') . Registry::get('fullRoute') . '&d=' . $parentDirectory . $hashString,
						'title' => Language::get('directory_parent', '_directory_lister')
					))
					->addClass(self::$_configArray['className']['types']['directoryParent'])
					->text(Language::get('directory_parent', '_directory_lister'));
				$outputDirectory .= '</li>';
			}

			/* process directory */

			foreach ($listerDirectoryArray as $key => $value)
			{
				$path = $directory . '/' . $value;
				$fileExtension = pathinfo($path, PATHINFO_EXTENSION);
				$text = $value;

				/* replace option */

				if ($optionArray['replace'])
				{
					foreach ($optionArray['replace'] as $replaceKey => $replaceValue)
					{
						if ($replaceKey === self::$_configArray['replaceKey']['extension'])
						{
							$replaceKey = $fileExtension;
						}
						$text = str_replace($replaceKey, $replaceValue, $text);
					}
				}

				/* handle directory */

				if (is_dir($path))
				{
					$outputDirectory .= '<li>';
					$outputDirectory .= $linkElement
						->copy()
						->attr(array(
							'href' => Registry::get('parameterRoute') . Registry::get('fullRoute') . '&d=' . $path . $hashString,
							'title' => Language::get('directory', '_directory_lister')
						))
						->addClass(self::$_configArray['className']['types']['directory'])
						->text($text);
					$outputDirectory .= $textSizeElement->copy();
					$outputDirectory .= $textDateElement
						->copy()
						->text(date($dateFormat, filectime($path)));
					$outputDirectory .= '</li>';
				}

				/* else handle file */

				else if (is_file($path) && array_key_exists($fileExtension, self::$_configArray['extension']))
				{
					$fileType = self::$_configArray['extension'][$fileExtension];
					$outputFile .= '<li>';
					$outputFile .= $linkElement
						->copy()
						->attr(array(
							'href' => Registry::get('root') . '/' . $path,
							'target' => '_blank',
							'title' => Language::get('file', '_directory_lister')
						))
						->addClass(self::$_configArray['className']['types'][$fileType])
						->text($text);
					$outputFile .= $textSizeElement
						->copy()
						->attr('data-unit', self::$_configArray['size']['unit'])
						->html(ceil(filesize($path) / self::$_configArray['size']['divider']));
					$outputFile .= $textDateElement
						->copy()
						->html(date($dateFormat, filectime($path)));
					$outputFile .= '</li>';
				}
			}

			/* collect list output */

			if ($outputDirectory || $outputFile)
			{
				$output = $listElement->html($outputDirectory . $outputFile);
			}
		}
		return $output;
	}
}
