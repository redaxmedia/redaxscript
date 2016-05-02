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

		/* writer */

		$writer = new XMLWriter();
		$writer->openMemory();
		$writer->setIndent(true);
		$writer->startDocument('1.0', Db::getSetting('charset'));
		$writer->startElementNS(null, 'urlset', 'http://www.sitemaps.org/schemas/sitemap/0.9');
		$writer->startElement('url');
		$writer->writeElement('loc', Registry::get('root'));
		$writer->endElement();

		/* process categories */

		foreach ($categories as $value)
		{
			$writer->startElement('url');
			$writer->writeElement('loc', Registry::get('root') . Registry::get('parameterRoute') . build_route('categories', $value->id));
			$writer->endElement();
		}

		/* process articles */

		foreach ($articles as $value)
		{
			$writer->startElement('url');
			$writer->writeElement('loc', Registry::get('root') . Registry::get('parameterRoute') . build_route('articles', $value->id));
			$writer->endElement();
		}
		$writer->endElement();
		$writer->endDocument();
		return $writer->outputMemory();
	}
}
