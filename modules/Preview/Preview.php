<?php
namespace Redaxscript\Modules\Preview;

use Redaxscript\Directory;
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
			$loader_modules_styles[] = 'modules/preview/styles/preview.css';
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
			Registry::set('title', l('preview', 'preview'));
			Registry::set('centerBreak', 1);
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
			$partialsPath = 'modules/preview/partials/';
			$partialsDirectory = new Directory($partialsPath);
			$partialsDirectoryArray = $partialsDirectory->get();

			/* collect partial output */

			$output = '<div class="preview clearfix">' . PHP_EOL;

			/* include as needed */

			if (Registry::get('secondParameter'))
			{
				$output .= self::_render(Registry::get('secondParameter'), $partialsPath . Registry::get('secondParameter') . '.phtml');
			}

			/* else include all */

			else
			{
				foreach ($partialsDirectoryArray as $partial)
				{
					$output .= self::_render(str_replace('.phtml', '', $partial), $partialsPath . $partial);
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
	 * @param $alias
	 * @param $path
	 *
	 * @return string
	 */

	protected static function _render($alias = null, $path = null)
	{
		/* collect title output */

		$output = '<h2 class="title_content" title="' . $alias . '">' . PHP_EOL;
		if (Registry::get('secondParameter') === $alias)
		{
			$output .= $alias;
		}
		else
		{
			$output .= '<a href="preview/' . $alias . '" title="' . $alias . '">' . $alias . '</a>' . PHP_EOL;
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
