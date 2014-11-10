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
	 * custom module setup
	 *
	 * @var array
	 */

	protected static $_module = array(
		'name' => 'Sitemap XML',
		'alias' => 'SitemapXml',
		'author' => 'Redaxmedia',
		'description' => 'Generate a sitemap XML',
		'version' => '2.2.0',
		'status' => 1,
		'access' => 0
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
			Registry::set('renderBreak', true);
			header('content-type: application/xml');
			self::render();
		}
	}

	/**
	 * render
	 *
	 * @since 2.2.0
	 *
	 * @return string
	 */

	public static function render()
	{
		$output = '<?xml version="1.0" encoding="' . Db::getSettings('charset') . '"?>';
		$output .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
		$output .= '<url><loc>' . Registry::get('root') . '</loc><lastmod>' . Registry::get('today') . '</lastmod><changefreq>daily</changefreq><priority>1.0</priority></url>';
		$output .= '</urlset>';
		return $output;
	}
}