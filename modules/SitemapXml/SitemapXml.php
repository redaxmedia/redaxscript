<?php
namespace Redaxscript\Modules\SitemapXml;

use Redaxscript\Db;
use Redaxscript\Module;
use Redaxscript\Registry;
use XMLWriter;

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
		if (Registry::get('firstParameter') === 'sitemap-xml')
		{
			Registry::set('renderBreak', true);
			header('content-type: application/xml');
			echo self::render();
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
		/* query categories */

		$categories = Db::forTablePrefix('categories')
			->where('status', 1)
			->whereNull('access')
			->orderByAsc('rank')
			->findMany();

		/* query articles */

		$articles = Db::forTablePrefix('articles')
			->where('status', 1)
			->whereNull('access')
			->orderByAsc('rank')
			->findMany();

		/* write xml */

		return self::_writeXML($categories, $articles);
	}

	/**
	 * @param object $categories
	 * @param object $articles
	 *
	 * @return string
	 */

	protected static function _writeXML($categories = null, $articles = null)
	{
		$writer = new XMLWriter();
		$writer->openMemory();
		$writer->setIndent(true);
		$writer->setIndentString('	');
		$writer->startDocument('1.0', Db::getSetting('charset'));
		$writer->startElement('urlset');
		$writer->writeAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
		$writer->startElement('url');
		$writer->writeElement('loc', Registry::get('root'));
		$writer->endElement();

		/* process categories */

		foreach ($categories as $value)
		{
			$writer->startElement('url');
			$writer->writeElement('loc', Registry::get('root') . Registry::get('parameterRoute') . build_route('categories', $value->id));
			$writer->writeElement('lastmod', date('c', strtotime($value->date)));
			$writer->endElement();
		}

		/* process articles */

		foreach ($articles as $value)
		{
			$writer->startElement('url');
			$writer->writeElement('loc', Registry::get('root') . Registry::get('parameterRoute') . build_route('articles', $value->id));
			$writer->writeElement('lastmod', date('c', strtotime($value->date)));
			$writer->endElement();
		}
		$writer->endElement();
		$writer->endDocument();
		return $writer->outputMemory(true);
	}
}
