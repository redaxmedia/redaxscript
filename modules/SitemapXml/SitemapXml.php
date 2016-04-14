<?php
namespace Redaxscript\Modules\SitemapXml;

use Redaxscript\Db;
use Redaxscript\Module;
use Redaxscript\Registry;

/**
 * generate a sitemap xml
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class SitemapXml extends Module
{
	/**
	 * array of the module
	 *
	 * @var array
	 */

	protected static $_moduleArray = array(
		'name' => 'Sitemap XML',
		'alias' => 'SitemapXml',
		'author' => 'Redaxmedia',
		'description' => 'Generate a sitemap XML',
		'version' => '3.0.0'
	);

	/**
	 * renderStart
	 *
	 * @since 2.2.0
	 */

	public static function renderStart()
	{
		if (Registry::get('firstParameter') === 'sitemap_xml')
		{
			header('content-type: application/xml');
			echo self::render();
			Registry::set('renderBreak', true);
		}
	}

	/**
	 * render
	 *
	 * @since 2.5.0
	 *
	 * @return string
	 */

	public static function render()
	{
		/* fetch categories */

		$categories = Db::forTablePrefix('categories')
			->where('status', 1)
			->whereNull('access')
			->orderByAsc('rank')
			->findMany();

		/* fetch articles */

		$articles = Db::forTablePrefix('articles')
			->where('status', 1)
			->whereNull('access')
			->orderByAsc('rank')
			->findMany();

		/* collect output */

		$output = '<?xml version="1.0" encoding="' . Db::getSetting('charset') . '"?>' . PHP_EOL;
		$output .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;
		$output .= '<url><loc>' . Registry::get('root') . '</loc></url>' . PHP_EOL;

		/* process categories */

		foreach ($categories as $value)
		{
			$route = $value->parent < 1 ? $value->alias : build_route('categories', $value->id);
			$output .= '<url><loc>' . Registry::get('root') . Registry::get('parameterRoute') . $route . '</loc></url>' . PHP_EOL;
		}

		/* process articles */

		foreach ($articles as $value)
		{
			$route = $value->category < 1 ? $value->alias : build_route('articles', $value->id);
			$output .= '<url><loc>' . Registry::get('root') . Registry::get('parameterRoute') . $route . '</loc></url>' . PHP_EOL;
		}
		$output .= '</urlset>';
		return $output;
	}
}
