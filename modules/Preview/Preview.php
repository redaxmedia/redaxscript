<?php
namespace Redaxscript\Modules\Preview;

use Redaxscript\Directory;
use Redaxscript\Language;
use Redaxscript\Module;
use Redaxscript\Registry;

/**
 * preview template elements
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class Preview extends Module
{
	/**
	 * custom module setup
	 *
	 * @var array
	 */

	protected static $_module = array(
		'name' => 'Preview',
		'alias' => 'Preview',
		'author' => 'Redaxmedia',
		'description' => 'Preview template elements',
		'version' => '2.2.0',
		'status' => 1,
		'access' => 0
	);

	/**
	 * loaderStart
	 *
	 * @since 2.2.0
	 */

	public static function loaderStart()
	{
		if (Registry::get('firstParameter') === 'preview')
		{
			global $loader_modules_styles;
			$loader_modules_styles[] = 'modules/Preview/styles/preview.css';
		}
	}

	/**
	 * renderStart
	 *
	 * @since 2.2.0
	 */

	public static function renderStart()
	{
		if (Registry::get('firstParameter') === 'preview')
		{
			Registry::set('title', Language::get('preview', '_preview'));
			Registry::set('description', Language::get('description', '_preview'));
			Registry::set('centerBreak', true);
		}
	}

	/**
	 * centerStart
	 *
	 * @since 2.2.0
	 */

	public static function centerStart()
	{
		if (Registry::get('firstParameter') === 'preview')
		{
			$partialsPath = 'modules/Preview/partials/';
			$partialsDirectory = new Directory($partialsPath);
			$partialsDirectoryArray = $partialsDirectory->getArray();

			/* collect partial output */

			$output = '<div class="preview clearfix">' . PHP_EOL;

			/* include as needed */

			if (Registry::get('secondParameter'))
			{
				$output .= self::render(Registry::get('secondParameter'), $partialsPath . Registry::get('secondParameter') . '.phtml');
			}

			/* else include all */

			else
			{
				foreach ($partialsDirectoryArray as $partial)
				{
					$alias = str_replace('.phtml', '', $partial);
					$output .= self::render($alias, $partialsPath . $partial);
				}
			}
			$output .= '</div>' . PHP_EOL;
			echo $output;
		}
	}

	/**
	 * render
	 *
	 * @since 2.2.0
	 *
	 * @param string $alias
	 * @param string $path
	 *
	 * @return string
	 */

	public static function render($alias = null, $path = null)
	{
		/* collect title output */

		$output = '<h2 class="title_content" title="' . $alias . '">' . PHP_EOL;
		if (Registry::get('secondParameter') === $alias)
		{
			$output .= $alias;
		}
		else
		{
			$output .= '<a href="' . Registry::get('rewriteRoute') . 'preview/' . $alias . '" title="' . $alias . '">' . $alias . '</a>' . PHP_EOL;
		}
		$output .=  '</h2>' . PHP_EOL;

		/* collect content output */

		if (file_exists($path))
		{
			ob_start();
			include_once($path);
			$output .= ob_get_clean();
		}
		return $output;
	}
}
