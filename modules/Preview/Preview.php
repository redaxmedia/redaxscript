<?php
namespace Redaxscript\Modules\Preview;

use Redaxscript\Directory;
use Redaxscript\Html;
use Redaxscript\Language;
use Redaxscript\Module;
use Redaxscript\Registry;
use Redaxscript\Template;

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
	 * array of the module
	 *
	 * @var array
	 */

	protected static $_moduleArray = array(
		'name' => 'Preview',
		'alias' => 'Preview',
		'author' => 'Redaxmedia',
		'description' => 'Preview template elements',
		'version' => '3.0.0'
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
			$loader_modules_styles[] = 'modules/Preview/assets/styles/preview.css';
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
			Registry::set('routerBreak', true);
		}
	}

	/**
	 * routerStart
	 *
	 * @since 3.0.0
	 */

	public static function routerStart()
	{
		if (Registry::get('firstParameter') === 'preview')
		{
			$partialsPath = 'modules/Preview/partials';
			$partialExtension = '.phtml';
			$partialsDirectory = new Directory();
			$partialsDirectory->init($partialsPath);
			$partialsDirectoryArray = $partialsDirectory->getArray();
			$secondParameter = Registry::get('secondParameter');

			/* collect partial output */

			$output = '<div class="rs-preview rs-clearfix">';

			/* include single */

			if ($secondParameter)
			{
				$output .= self::render($secondParameter, $partialsPath . '/' . $secondParameter . $partialExtension);
			}

			/* else include all */

			else
			{
				foreach ($partialsDirectoryArray as $partial)
				{
					$alias = str_replace($partialExtension, '', $partial);
					$output .= self::render($alias, $partialsPath . '/' . $partial);
				}
			}
			$output .= '</div>';
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
		$titleElement = new Html\Element();
		$titleElement->init('h2', array(
			'class' => 'rs-title-preview',
			'title' => $alias
		));
		$linkElement = new Html\Element();
		$linkElement->init('a', array(
			'href' => Registry::get('secondParameter') === $alias ? null : Registry::get('rewriteRoute') . 'preview/' . $alias,
			'title' => $alias
		));

		/* collect output */

		$output = $titleElement->html($linkElement->text($alias));
		$output .= Template::partial($path);
		return $output;
	}
}
