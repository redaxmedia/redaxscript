<?php
namespace Redaxscript\Modules\DirectoryLister;

use Redaxscript\Db;
use Redaxscript\Directory;
use Redaxscript\Element;
use Redaxscript\Registry;

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
		if ($directory)
		{
			/* html elements */

			$linkElement = new Element('a', array(
					'class' => self::$_config['className']['link'])
			);
			$textElement = new Element('span');
			$listElement = new Element('ul', array(
					'class' => self::$_config['className']['list'])
			);

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
					$outputDirectory .= $linkElement->attr(array(
						'href' => Registry::get('fullRoute') . '?directory=' . $directory . '/' . $value,
						'title' => $value
					))->text($value);
					$outputDirectory .= '</li>';
				}
				else
				{
					$outputFile .= '<li>';
					$outputFile .= $linkElement->attr(array(
						'href' => $directory . '/' . $value,
						'title' => $value
					))->text($value);
					$outputFile .= $textElement
						->copy()
						->addClass(self::$_config['className']['textSize'])
						->html(ceil(filesize($directory . '/' . $value) / 1024));
					$outputFile .= $textElement
						->copy()
						->addClass(self::$_config['className']['textDate'])
						->html(date($dateFormat, filectime($directory . '/' . $value)));
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
