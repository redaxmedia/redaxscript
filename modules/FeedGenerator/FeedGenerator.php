<?php
namespace Redaxscript\Modules\FeedGenerator;

use Redaxscript\Dater;
use Redaxscript\Db;
use Redaxscript\Header;
use Redaxscript\Model;
use Redaxscript\Module;
use XMLWriter;
use function strip_tags;

/**
 * provide feeds to your audience
 *
 * @since 2.3.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class FeedGenerator extends Module\Module
{
	/**
	 * array of the module
	 *
	 * @var array
	 */

	protected static $_moduleArray =
	[
		'name' => 'Feed Generator',
		'alias' => 'FeedGenerator',
		'author' => 'Redaxmedia',
		'description' => 'Provide feeds to your audience',
		'version' => '4.4.0'
	];

	/**
	 * renderStart
	 *
	 * @since 2.3.0
	 */

	public function renderStart() : void
	{
		if ($this->_registry->get('firstParameter') === 'module' && $this->_registry->get('secondParameter') === 'feed-generator')
		{
			$this->_registry->set('renderBreak', true);
			if ($this->_registry->get('thirdParameter') === 'articles')
			{
				echo $this->render('articles');
			}
			if ($this->_registry->get('thirdParameter') === 'comments')
			{
				echo $this->render('comments');
			}
		}
	}

	/**
	 * render
	 *
	 * @since 2.3.0
	 *
	 * @param string $table name of the table
	 *
	 * @return string
	 */

	public function render(string $table = null) : string
	{
		/* query result */

		$resultArray[$table] = Db::forTablePrefix($table)
			->whereLanguageIs($this->_registry->get('language'))
			->whereNull('access')
			->where('status', 1)
			->orderBySetting('rank')
			->findMany();

		/* write xml */

		Header::contentType('application/atom+xml');
		return $this->_writeXML($resultArray);
	}

	/**
	 * @param array $resultArray
	 *
	 * @return string
	 */

	protected function _writeXML(array $resultArray = []) : string
	{
		$writer = new XMLWriter();
		$contentModel = new Model\Content();
		$settingModel = new Model\Setting();
		$dater = new Dater();
		$dater->init($this->_registry->get('now'));
		$root = $this->_registry->get('root');
		$parameterRoute = $this->_registry->get('parameterRoute');

		/* prepare href */

		$href = $root . '/' . $parameterRoute . $this->_registry->get('fullRoute');
		if ($this->_request->getQuery('l'))
		{
			$href .= $this->_registry->get('languageRoute') . $this->_registry->get('language');
		}

		/* write xml */

		$writer->openMemory();
		$writer->setIndent(true);
		$writer->setIndentString('	');
		$writer->startDocument('1.0', $settingModel->get('charset'));
		$writer->startElement('feed');
		$writer->writeAttribute('xmlns', 'http://www.w3.org/2005/Atom');
		$writer->startElement('link');
		$writer->writeAttribute('type', 'application/atom+xml');
		$writer->writeAttribute('href', $href);
		$writer->writeAttribute('rel', 'self');
		$writer->endElement();
		$writer->writeElement('id', $href);
		$writer->writeElement('title', $settingModel->get('title'));
		$writer->writeElement('updated', $dater->getDateTime()->format('c'));
		$writer->startElement('author');
		$writer->writeElement('name', $settingModel->get('author'));
		$writer->endElement();

		/* process result */

		foreach ($resultArray as $table => $result)
		{
			foreach ($result as $value)
			{
				$dater->init($value->date);
				$writer->startElement('entry');
				$writer->writeElement('id', $root . '/' . $parameterRoute . $contentModel->getRouteByTableAndId($table, $value->id));
				$writer->writeElement('title', $value->title);
				$writer->writeElement('updated', $dater->getDateTime()->format('c'));
				$writer->writeElement('content', strip_tags($value->text));
				$writer->endElement();
			}
		}
		$writer->endElement();
		$writer->endDocument();
		return $writer->outputMemory(true);
	}
}
