<?php
namespace Redaxscript\Modules\SitemapXml;

use Redaxscript\Dater;
use Redaxscript\Db;
use Redaxscript\Header;
use Redaxscript\Model;
use Redaxscript\Module;
use XMLWriter;

/**
 * submit the sitemap as xml
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
		'description' => 'Submit the sitemap as XML',
		'version' => '4.3.0'
	];

	/**
	 * renderStart
	 *
	 * @since 2.2.0
	 */

	public function renderStart() : void
	{
		if ($this->_registry->get('firstParameter') === 'module' && $this->_registry->get('secondParameter') === 'sitemap-xml')
		{
			$this->_registry->set('renderBreak', true);
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
			->whereNull('access')
			->where('status', 1)
			->orderByAsc('rank')
			->findMany();

		/* query articles */

		$articles = Db::forTablePrefix('articles')
			->whereNull('access')
			->where('status', 1)
			->orderByAsc('rank')
			->findMany();

		/* write xml */

		Header::contentType('application/xml');
		return $this->_writeXML($categories, $articles);
	}

	/**
	 * write xml
	 *
	 * @since 2.5.0
	 *
	 * @param object $categories
	 * @param object $articles
	 *
	 * @return string
	 */

	protected function _writeXML(object $categories = null, object $articles = null) : string
	{
		$writer = new XMLWriter();
		$categoryModel = new Model\Category();
		$articleModel = new Model\Article();
		$settingModel = new Model\Setting();
		$dater = new Dater();
		$root = $this->_registry->get('root');
		$parameterRoute = $this->_registry->get('parameterRoute');

		/* write xml */

		$writer->openMemory();
		$writer->setIndent(true);
		$writer->setIndentString('	');
		$writer->startDocument('1.0', $settingModel->get('charset'));
		$writer->startElement('urlset');
		$writer->writeAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
		$writer->startElement('url');
		$writer->writeElement('loc', $root);
		$writer->endElement();

		/* process categories */

		foreach ($categories as $value)
		{
			$dater->init($value->date);
			$writer->startElement('url');
			$writer->writeElement('loc', $root . '/' . $parameterRoute . $categoryModel->getRouteById($value->id));
			$writer->writeElement('lastmod', $dater->getDateTime()->format('c'));
			$writer->endElement();
		}

		/* process articles */

		foreach ($articles as $value)
		{
			$dater->init($value->date);
			$writer->startElement('url');
			$writer->writeElement('loc', $root . '/' . $parameterRoute . $articleModel->getRouteById($value->id));
			$writer->writeElement('lastmod', $dater->getDateTime()->format('c'));
			$writer->endElement();
		}
		$writer->endElement();
		$writer->endDocument();
		return $writer->outputMemory(true);
	}
}
