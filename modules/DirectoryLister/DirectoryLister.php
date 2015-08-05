<?php
namespace Redaxscript\Modules\DirectoryLister;

use Redaxscript\Db;
use Redaxscript\Directory;
use Redaxscript\Element;
use Redaxscript\Request;

/**
 * simple directory lister
 *
 * @since 2.5.0
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
		'version' => '2.5.0'
	);

	/**
	 * loaderStart
	 *
	 * @since 2.5.0
	 */

	public static function loaderStart()
	{
		global $loader_modules_styles;
		$loader_modules_styles[] = 'modules/DirectoryLister/styles/directory_lister.css';
	}

	/**
	 * render
	 *
	 * @since 2.5.0
	 *
	 * @param string $directory
	 * @param mixed $exclude
	 *
	 * @return string
	 */

	public static function render($directory = null, $exclude = null)
	{
		$output = '';
		$outputDirectory = '';
		$outputFile = '';

		/* handle query */

		$directoryQuery = Request::getQuery('dir');
		if ($directoryQuery)
		{
			$directory = $directoryQuery;
		}

		/* has directory */

		if (is_dir($directory))
		{
			/* html elements */

			$linkElement = new Element('a', array(
					'class' => self::$_config['className']['link']
			));
			$textSizeElement = new Element('span', array(
					'class' => self::$_config['className']['textSize']
			));
			$textDateElement = new Element('span', array(
					'class' => self::$_config['className']['textDate']
			));
			$listElement = new Element('ul', array(
					'class' => self::$_config['className']['list']
			));

			/* list directory object */

			$listDirectory = new Directory();
			$listDirectory->init($directory, $exclude);
			$listDirectoryArray = $listDirectory->getArray();

			/* date format */

			$dateFormat = Db::getSettings('date');

			/* process directory */

			foreach ($listDirectoryArray as $key => $value)
			{
				if (is_dir($directory . '/' . $value))
				{
					$outputDirectory .= '<li>';
					$outputDirectory .= $linkElement
						->copy()
						->attr(array(
							'href' => '?dir=' . $directory . '/' . $value,
							'title' => $value
						))
						->addClass(self::$_config['className']['types']['directoryClosed'])
						->text($value);
					$outputDirectory .= '</li>';
				}
				else
				{
					$outputFile .= '<li>';
					$outputFile .= $linkElement
						->copy()
						->attr(array(
							'href' => $directory . '/' . $value,
							'title' => $value
						))
						->addClass(self::$_config['className']['types']['fileBlank'])
						->text($value);
					$outputFile .= $textSizeElement
						->attr('data-unit', self::$_config['size']['unit'])
						->html(ceil(filesize($directory . '/' . $value) / self::$_config['size']['number']));
					$outputFile .= $textDateElement->html(date($dateFormat, filectime($directory . '/' . $value)));
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
