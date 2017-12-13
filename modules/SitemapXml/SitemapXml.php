<?php
namespace Redaxscript\Modules\SitemapXml;

use Redaxscript\Db;
use Redaxscript\Model;
use Redaxscript\Module;
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

class SitemapXml extends Module\Module
{
	/**
	 * array of the module
	 *
	 * @var array
	 */

	protected static $_moduleArray =
	[
		'name' => 'Sitemap XML',
		'alias' => 'SitemapXml',
		'author' => 'Redaxmedia',
		'description' => 'Generate a sitemap XML',
		'version' => '3.3.0'
	];

	/**
	 * renderStart
	 *
	 * @since 2.2.0
	 */

	public function renderStart()
	{
		if ($this->_registry->get('firstParameter') === 'sitemap-xml')
		{
			$this->_registry->set('renderBreak', true);
			header('content-type: application/xml');
			echo $this->render();
		}
	}

	/**
	 * render
	 *
	 * @since 2.5.0
	 *
	 * @return string
	 */

	public function render() : string
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

		return $this->_writeXML($categories, $articles);
	}

	/**
	 * @param object $categories
	 * @param object $articles
	 *
	 * @return string
	 */

	protected function _writeXML($categories = null, $articles = null)
	{
		$writer = new XMLWriter();
		$categoryModel = new Model\Category();
		$articleModel = new Model\Article();
		$settingModel = new Model\Setting();

		/* write xml */

		$writer->openMemory();
		$writer->setIndent(true);
		$writer->setIndentString('	');
		$writer->startDocument('1.0', $settingModel->get('charset'));
		$writer->startElement('urlset');
		$writer->writeAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
		$writer->startElement('url');
		$writer->writeElement('loc', $this->_registry->get('root'));
		$writer->endElement();

		/* process categories */

		foreach ($categories as $value)
		{
			$writer->startElement('url');
			$writer->writeElement('loc', $this->_registry->get('root') . '/' . $this->_registry->get('parameterRoute') . $categoryModel->getRouteById($value->id));
			$writer->writeElement('lastmod', date('c', strtotime($value->date)));
			$writer->endElement();
		}

		/* process articles */

		foreach ($articles as $value)
		{
			$writer->startElement('url');
			$writer->writeElement('loc', $this->_registry->get('root') . '/' . $this->_registry->get('parameterRoute') . $articleModel->getRouteById($value->id));
			$writer->writeElement('lastmod', date('c', strtotime($value->date)));
			$writer->endElement();
		}
		$writer->endElement();
		$writer->endDocument();
		return $writer->outputMemory(true);
	}
}
