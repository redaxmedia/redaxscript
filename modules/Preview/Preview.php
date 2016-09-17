<?php
namespace Redaxscript\Modules\Preview;

use Redaxscript\Directory;
use Redaxscript\Head;
use Redaxscript\Html;
use Redaxscript\Language;
use Redaxscript\Module;
use Redaxscript\Registry;
use Redaxscript\Template;

/**
 * preview template elements
 *
 * @since 3.0.0
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

	protected static $_moduleArray =
	[
		'name' => 'Preview',
		'alias' => 'Preview',
		'author' => 'Redaxmedia',
		'description' => 'Preview template elements',
		'version' => '3.0.0'
	];

	/**
	 * renderStart
	 *
	 * @since 2.2.0
	 */

	public static function renderStart()
	{
		if (Registry::get('firstParameter') === 'preview')
		{
			Registry::set('metaTitle', Language::get('preview', '_preview'));
			Registry::set('metaDescription', Language::get('description', '_preview'));
			Registry::set('routerBreak', true);

			/* link */

			$link = Head\Link::getInstance();
			$link
				->init()
				->appendFile('modules/Preview/dist/styles/preview.min.css');
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

			$output = '<div class="rs-is-preview rs-fn-clearfix">';

			/* include single */

			if ($secondParameter)
			{
				$output .= self::render($secondParameter, $partialsPath . '/' . $secondParameter . $partialExtension);
			}

			/* else include all */

			else
			{
				foreach ($partialsDirectoryArray as $value)
				{
					$alias = str_replace($partialExtension, '', $value);
					$output .= self::render($alias, $partialsPath . '/' . $value);
				}
			}
			$output .= '</div>';
			echo $output;
		}
	}

	/**
	 * render
	 *
	 * @since 3.0.0
	 *
	 * @param string $alias
	 * @param string $path
	 *
	 * @return string
	 */

	public static function render($alias = null, $path = null)
	{
		$titleElement = new Html\Element();
		$titleElement->init('h2',
		[
			'class' => 'rs-title-preview',
			'id' => $alias
		]);
		$linkElement = new Html\Element();
		$linkElement->init('a',
		[
			'href' => Registry::get('secondParameter') === $alias ? Registry::get('parameterRoute') . 'preview#' . $alias : Registry::get('parameterRoute') . 'preview/' . $alias
		])
		->text(Registry::get('secondParameter') === $alias ? Language::get('back') : $alias);

		/* collect output */

		$output = $titleElement->html($linkElement);
		$output .= Template\Tag::partial($path);
		return $output;
	}
}
